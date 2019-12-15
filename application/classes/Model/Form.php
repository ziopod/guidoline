<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Forms models
 *
 * @package    Guidoline
 * @category   Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Form extends ORM{

	/**
	 * @var Array  Has Many relationships
	 */
	protected $_has_many = array(
		'members' => array(
			'through'	=> 'dues',
		),
		'dues' => array(),
		'currencies' => array(),
	);

	/**
	 * @var Array  Belongs to relationships
	 */
	protected $_belongs_to = array(
		'currency' => array(
			'foreign_key' => 'currency_code',
    ),
   );

	/**
	 * @var Array  Default sorting order
	 */
	protected $_sorting = array(
		'created'	=> 'asc',
	);

	/**
   * @var Integer  All actives dues storage
	 */
  protected $_dues;

  /**
   * @var Array  Find all dues storage
   */
  protected $_dues_all;

	/**
	 * @var Array Currencies storage
	 */
	protected $_currencies;

	/**
	 * Validation rules
	 *
	 * @return Array
	 */
	public function rules()
	{
		return array(
			'title' => array(
        array('not_empty'),
        array('max_length', array(':value', 35)),
				array(array($this, 'unique'), array(':field', ':value')),
      ),
      'heading' => array(
        array('not_empty'),
      ),
      // 'duration' => array(
      //   array('not_empty')
      // ),
			'period_start' => array(
				array('date'),
			),
		);
	}

	/**
	 * Filters
	 *
	 * @return Array
	 */
	public function filters()
	{
		return array(
			'title' => array(
        array('ucfirst'),
        array(function($value)
        {
          if ( ! $this->slug)
          {
            $this->slug = URL::title($value);
          }

          return $value;
        }),
      ),
      'heading' => array(
        array(function($value) {
          if ( ! $value)
          {
            $value = $this->title;
          }

          return $value;
        })
      ),
      'price' => array(
        array(function($value) {
          if ( ! $value)
          {
            $value = 0;
          }

          return $value;
        }),
      ),
      'duration' => array(
        array(function($value) {

          if ( ! $value)
          {
            $value = '3 month';
          }
          return $value;
        }),
      ),
      'free_price' => array(
        array(function($value)
        {
          if ( ! $value)
          {
            return FALSE;
          }

          return TRUE;
        })
      ),
			'date_start' => array(
        array('ORM::nullish'),
      ),
		);
	}

	/**
	 * Labels map for column name
	 *
	 * Use for message errors
	 *
	 * @return Array
	 */
	public function labels()
	{
		return array(
			'title' => __('Titre'),
			'heading' => __('Intitulé'),
			'period_start' => __('début de la période'),
			'start_at_due' => __('valide à partir de la date d\'adhésion'),
		);
	}

	/**
	 * Additionnal related embeddable data
	 *
	 * ~~~
	 * array(
	 *	index_name => relationship_name
	 *	index_name => array(
	 *		'value_index_ame' => 'specific_value_name'
	 *	)
	 * ~~~
	 *
	 * @return Array
	 */
	public function embeddable()
	{
		return array(
			'currency' => array(
				'entity' => 'entity',
			),
			'currencies' => 'currencies',
      'dues' => 'dues',
      'dues_all' => 'dues_all',
      'pretty_duration' => 'pretty_duration',
		);
	}

	/**
	 * Form url
	 *
	 * @return String
	 */
	public function url()
	{
		return URL::site(Route::get('form.detail')->uri(array('form_id' => $this->id)), TRUE);
	}

	/**
	 * Form edit url
	 *
	 * @return String
	 */
	public function url_edit()
	{
		return URL::site(Route::get('form.edit')->uri(array('form_id' => $this->id)), TRUE);
  }

  /**
   *
   * date_start  start_at_due   description
   * NULL        1              Commence à la date de cotisation, fini à durée
   * NULL        0              Idem
   * Date        1              Commence à la date de cotisation, fini à form
   * Date        0              commence à la date de form, fini à form
   *
   */
  /**
   * Period will start at date start form.
   *
   * @return Boolean
   */
  public function is_period_start_at_form()
  {
    return $this->date_start && ! $this->start_at_due;
  }

  /**
   * Period will start at due creation
   *
   * @return Boolean
   */
  public function is_period_start_at_due()
  {
    return ! $this->is_period_start_at_form();
  }

  /**
   * Due can start anytime
   *
   * @return Boolean
   */
  // public function free_period_start()
  // {
  //   return $this->date_start !== NULL && $this->start_at_due;
  // }

  /**
   * Due will end at start date + period
   *
   * @return Boolean
   */
  public function is_due_end_at_period()
  {
    return $this->date_start === NULL;
  }

  /**
   * Due will end at date end form
   *
   * @return Boolean
   */
  public function is_due_end_at_form()
  {
    return ! $this->is_due_end_at_period();
  }

  /**
   * Period due
   *
   * Required for form date : date_start and ! start_at_due
   * Required for due date : start_at_due
   */
  public function period_type()
  {
    return $this->is_period_start_at_due() ? 'due' : 'form';
  }

  /**
	 * Pretty period type
	 *
	 * @return String
	 */
	public function pretty_period_type()
	{
    return __('period.type.' . $this->period_type());
  }


	/**
	 * Pretty period start
	 *
	 * @return String
	 */
	public function pretty_date_start()
	{
		return trim(strftime(__('date.medium'), strtotime($this->date_start)));
	}

	/**
	 * Period end
	 *
	 * @return String
	 */
	public function pretty_date_end()
	{
		return trim(strftime(__('date.medium'), strtotime($this->date_end())));
	}

	/**
	 * Formattted duration, fun with time span!
	 *
   * @todo  Trop complexe et impréçis, se contenter de traduire le format de
   * date textuel PHP (en entrée et en sortie). Cela permettras d'améliorer le
   * formulaire d'édition du butlletin d'adhésion.
	 * @return String
	 */
	public function pretty_duration()
	{
    $date_span = Date::span($this->duration(), $this->duration() + $this->duration());
		$years = $date_span['years'];
		$months = $date_span['months'];
		$weeks = $date_span['weeks'];
		$days = $date_span['days'];
		$formatted_years = NULL;
		$formatted_months = NULL;
		$formatted_weeks = NULL;
		$formatted_days = NULL;
		$after_years_sep = NULL;
		$after_months_sep = NULL;
		$after_weeks_sep = NULL;

		if ($years > 1)
		{
			$formatted_years = $years . ' ' . __('years');
		}
		elseif ($years > 0)
		{
			$formatted_years = $years . ' ' . __('year');
		}

		if ($months > 1)
		{
			$formatted_months = $months . ' ' . __('months');
		}
		elseif ($months > 0)
		{
			$formatted_months = $months . ' ' . __('month'); // French language exception
		}

    if ($weeks > 1)
    {
      $formatted_weeks = $weeks . ' ' . __('weeks');
    }
    elseif ($weeks > 0)
    {
      $formated_weeks = $weeks . ' ' . __('week');
    }

		if ($days > 1)
		{
			$formatted_days = $days . ' ' . __('days');
		}
		elseif ($days > 0)
		{
			$formatted_days = $days . ' ' . __('day');
		}

		// 1, 1, 1, 1
		if ($years > 0 AND $months > 0 AND $weeks > 0 AND $days > 0)
		{
			$after_years_sep = ', ';
			$after_months_sep = ', ';
			$after_weeks_sep = ' ' . __('and') . ' ';
		}
		// 1, 1, 1, 0
		elseif ($years > 0 AND $months > 0 AND $weeks > 0 AND $days == 0)
		{
			$after_years_sep = ', ';
			$after_months_sep = ' ' . __('and') . ' ';
		}
		// 1, 1, 0, 1
		elseif ($years > 0 AND $months > 0 AND $weeks == 0 AND $days > 0)
		{
			$after_years_sep = ', ';
			$after_months_sep = ' ' . __('and') . ' ';
		}
		// 1, 0, 1, 1
		elseif ($years > 0 AND $months == 0 AND $weeks > 0 AND $days > 0)
		{
			$after_years_sep = ', ';
			$after_weeks_sep = ' ' . __('and') . ' ';
    }
    // 0, 1, 1, 1
    elseif($years == 0 AND $months > 0 AND $weeks > 0 AND $days > 0)
    {
      $after_months_sep = ', ';
      $after_weeks_sep = ' ' . __('and') . ' ';
    }
    // 1, 1, 0, 0
		elseif ($years > 0 AND $months > 0 AND $weeks == 0 AND $days == 0)
		{
			$after_years_sep = ' ' . __('and') . ' ';
		}

		$result = $formatted_years . $after_years_sep . $formatted_months . $after_months_sep . $formatted_weeks . $after_weeks_sep . $formatted_days;
		return $result ? $result : 0 . ' ' . __('day');
	}

	/**
	 * Formatted creation date
	 *
	 * @return String
	 */
	public function pretty_created()
	{
		return strftime(__('datetime.long'), strtotime($this->created));
  }

  /**
   * Date de fin de validité du bulletin
   *
   * @return String
   */
  public function date_end()
  {
    return Date('Y-m-d', strtotime($this->date_start) + $this->duration());
  }

  /**
   * Durée du bulletin
   *
   * @todo  À améliorer, tenir compte des années bisextilles
   * // Vrai nombres de jours dans l'année (date('z', mktime(0, 0, 0, 12, 31, Date::YEAR)) + 1 ) * 24 * 60 * 60; ou Date::YEAR
   * Exploiter le calcul de mysql : https://www.php.net/manual/fr/function.strtotime.php#111989
   * SELECT DATE_ADD( '2009-01-31', INTERVAL 1 MONTH );
   * Exploiter : https://www.php.net/manual/fr/class.dateperiod.php
   * @return String
   */
  public function duration()
  {
    return strtotime($this->duration, 0);
    // return strtotime(date('Y', Date::YEAR) . '-03-01' . ' ' . $this->duration) - strtotime(date('Y', Date::YEAR) . '-03-01');
    // return strtotime(date('Y') . ' ' . $this->duration) - strtotime(date('Y'));
  }

  /**
   * Determine si le bulletin est renouvelable
   *
   * @return Boolean
   */
  public function is_renewable()
  {
    return $this->date_start === NULL;
  }

	/**
	 * Form actives dues
	 *
	 * @return ORM
	 */
	public function dues()
	{
		if ( ! $this->_dues)
		{
			$this->_dues = array(
				'records' => array(),
				'records_count' => NULL,
			);

			foreach ($this->dues->where('date_end', '>=', date('Y-m-d'))->find_all() as $due)
			{
				$this->_dues['records'][]['due'] = $due->as_array('membership,currencies,currency');
			}

      $this->_dues['records_count'] = count($this->_dues['records']);
    }

		return $this->_dues;
	}

	/**
	 * All form dues
	 *
	 * @return Array  Array of ORM objects
	 */
	public function dues_all()
	{
		if ( ! $this->_dues_all)
		{
			$this->_dues_all = array(
				'records' => array(),
        'records_count' => 0,
        'records_active_count' => 0,
			);

			foreach ($this->dues->find_all() as $due)
			{
        $this->_dues_all['records_active_count'] += $due->is_active() ? 1 : 0;
				$this->_dues_all['records'][]['due'] = $due->as_array();
			}

      $this->_dues_all['records_count'] = count($this->_dues_all['records']);

		}

		return $this->_dues_all;
  }

  /**
   * Current currency
   *
   * @return Array
   */
  public function currency()
  {
    return $this->currency->as_array();
  }

	/**
   * Get currencies and mark current currency
	 *
   * @return Array  Array of ORM objects
	 */
  public function currencies()
	{
    if ( ! $this->_currencies)
		{
      $current_currency_code = $this->currency_code
        ? $this->currency_code
        : $this->_table_columns['currency_code']['column_default'];
      $this->_currencies = $this->currencies->find_all_as_array($current_currency_code);
		}

		return $this->_currencies;
	}

	/**
	 * Extend parent::values()
	 * Force boolean false value definition from HTML form checkboxes and radios.
	 *
	 * @return ORM
	 */
	public function values(array $values, array $excepted = NULL)
	{
		$values['free_price'] = (bool) Arr::get($values, 'free_price', FALSE);
		$values['start_at_due'] = (bool) Arr::get($values, 'start_at_due', FALSE);
		$values['is_active'] = (bool) Arr::get($values, 'is_active', FALSE);

		return parent::values($values, $excepted);
	}

  /**
   * Populate HTML form
   *
   * @param   Array   $errors   ORM Validation exception errors
   * @return  Array
   */
  public function html_form($errors = array())
  {
    return array(
      'title' => 'Ajouter un bulletin',
      'title_loaded' => 'Modififier le bulletin "' . $this->title .'"',
      'action' => $this->url_edit() . '#form_form',
      'html_form_id' => 'edit_form_' . $this->pk(),
      'form_id' => $this->pk(),
      'loaded' => $this->loaded(),
      'data' => array(
        'title' => array(
          'field' => array(
            'label'     => __('Titre'),
            'help'      => '35 caractères maximum',
            'maxlength' => 35,
            'name'      => 'title',
            'id'        => 'title',
            'value'     => $this->title,
            'required'  => 'required',
            'error'     => Arr::get($errors, 'title'),
          )
        ),
        'heading' =>  array(
          'field' => array(
            'label'     => __('Intitulé'),
            'help'      => 'Ce champ apparaitras sur les factures (70 caractères maximum)',
            'maxlength' => 70,
            'name'      => 'heading',
            'id'        => 'heading',
            'value'     => $this->heading,
            'error'     => Arr::get($errors, 'heading'),
          )
        ),
        'description' =>  array(
          'field' => array(
            'label' => __('Description'),
            'name'  => 'description',
            'id'    => 'description',
            'value' => $this->description,
          )
        ),
        'price' =>  array(
          'field' => array(
            'label' => __('Tarif'),
            'name'  => 'price',
            'id'    => 'price',
            'value' => $this->price,
            'error' => Arr::get($errors, 'price'),
          )
        ),
        'select_currencies' => array(
          'field' => array(
            'label' => __('Devise'),
            'name'  => 'currency_code',
            'id'    => 'currency_code',
            'options' => $this->records_to_options($this->currencies()),
          )
        ),
        'free_price' =>  array(
          'field' => array(
            'label'   => __('Prix libre'),
            'name'    => 'free_price',
            'id'      => 'free_price',
            'checked' => $this->free_price ? 'checked' : NULL,
          )
        ),
        'date_start' =>  array(
          'field' => array(
            'label' => __('Date de début'),
            'help'  => 'Date au format YYYY-MM-DD',
            'name'  => 'date_start',
            'id'    => 'date_start',
            'value' => $this->date_start,
            'error' => Arr::get($errors, 'date_start'),
          )
        ),
        'duration' =>  array(
          'field' => array(
            'label' => __('Durée'),
            'help'  => 'Durée exprimée en années ou en mois',
            'placeholder' => '1 year, 3 months',
            'name'  => 'duration',
            'id'    => 'duration',
            'value' => $this->duration,
            'error' => Arr::get($errors, 'duration'),
          )
        ),
        'start_at_due' =>  array(
          'field' => array(
            'label' => __('Commence à la date d\'adhésion'),
            'name'  => 'start_at_due',
            'id'    => 'start_at_due',
            'checked' => $this->start_at_due ? 'checked' : NULL,
          )
        ),
        'is_active' => array(
          'field' => array(
            'label'   => __('Actif'),
            'name'    => 'is_active',
            'id'      => 'is_active',
            'value'   => $this->is_active,
            'checked' => $this->is_active ? 'checked' : NULL,
          )
        ),
      )
    );
  }
	/**
	 * Extend ORM as_array for include processing on data
	 *
	 * @param  String  $embed_paths   Related data to embed
	 * @return Array
	 */
	public function as_array($embed_paths = NULL)
	{
    $object = parent::as_array($embed_paths);
    $object['currency'] = $this->currency();
		// Periods stuffs
		$object['pretty_period_type'] = $this->pretty_period_type();
		$object['is_period_start_at_due'] = $this->is_period_start_at_due();
		$object['is_period_start_at_form'] = $this->is_period_start_at_form();
		$object['is_due_end_at_period'] = $this->is_due_end_at_period();
		$object['is_due_end_at_form'] = $this->is_due_end_at_form();
		$object['is_renewable'] = $this->is_renewable();
		$object['date_end'] = $this->date_end();
		$object['pretty_created'] = $this->pretty_created();
		$object['pretty_date_start'] = $this->pretty_date_start();
		$object['pretty_date_end'] = $this->pretty_date_end();
    $object['pretty_duration'] = $this->pretty_duration();
    // URLs
    $object['url'] = $this->url();
    $object['url_edit'] = $this->url_edit();

    $embed = $this->_embed($embed_paths);
		return array_merge($object, $embed);
	}
}
