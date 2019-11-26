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
		'created'	=> 'desc',
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
				array(array($this, 'unique'), array(':field', ':value')),
			),
			'period_start' => array(
				array('not_empty'),
				array(function(Validation $validation, $reference_field, $compared_field)
				{
					// If lesser than end date (Lesser than validation)
					if ($validation[$reference_field] > $validation[$compared_field])
					{
						$validation->error($reference_field, 'greater_than', array($compared_field));
						$validation->error($compared_field, 'lesser_than', array($reference_field));
					}
				}, array(':validation', ':field', 'period_end')),
			),
			'period_end' => array(
				array('not_empty'),
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
			),
			'period_start' => array(
				array(function($value)
				{
					if ($value === '0000-00-00')
					{
						return NULL;
					}

					return $value;
				}),
			),
			'period_end' => array(
				array(function($value)
				{
					if ($value === '0000-00-00')
					{
						return NULL;
					}

					return $value;
				}),
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
			'title' => __('titre'),
			'period_start' => __('début de la période'),
			'period_end' => __('fin de la période'),
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
	 * Period type form
	 *
	 * @return Boolean
	 */
	public function period_type_form()
	{
		return $this->period_type === 'form';
	}

	/**
	 * Period type due
	 *
	 * @return Boolean
	 */
	public function period_type_due()
	{
		return $this->period_type === 'due';
	}

	/**
	 * Pretty period type
	 *
	 * @return String
	 */
	public function pretty_period_type()
	{
		switch ($this->period_type) {
			case 'form':
				return __('à la date du bulletin');
				break;
			case 'due':
				return __('à la date de cotisation');
				break;
			default:
				return NULL;
				break;
		}
	}

	/**
	 * Pretty period start
	 *
	 * @return String
	 */
	public function pretty_period_start()
	{
		return strftime(__('date.medium'), strtotime($this->period_start));
	}

	/**
	 * Period end
	 *
	 * @return String
	 */
	public function pretty_period_end()
	{
		return strftime(__('date.medium'), strtotime($this->period_end));
	}

	/**
	 * Formatted month start
	 *
	 * @return String
	 */
	public function pretty_month_start()
	{
		return strftime(__('date.month'), strtotime($this->period_start));
	}

	/**
	 * Formatted day start
	 *
	 * @return String
	 */
	public function pretty_day_start()
	{
		return strftime(__('date.day'), strtotime($this->period_start));
	}

	/**
	 * Formattted duration, fun with time span!
	 *
	 * @return string
	 */
	public function pretty_duration()
	{
    $date_span = $this->duration();
		$years = $date_span['years'];
		$months = $date_span['months'];
		$days = $date_span['days'];
		$formatted_years = NULL;
		$formatted_months = NULL;
		$formatted_days = NULL;
		$after_years_sep = NULL;
		$after_months_sep = NULL;

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

		if ($days > 1)
		{
			$formatted_days = $days . ' ' . __('days');
		}
		elseif ($days > 0)
		{
			$formatted_days = $days . ' ' . __('day');
		}


		// 1, 1, 1
		if ($years > 0 AND $months > 0 AND $days > 0)
		{
			$after_years_sep = ', ';
			$after_months_sep = ' ' . __('and') . ' ';
		}
		// 1, 1, 0
		elseif ($years > 0 AND $months > 0 AND $days == 0)
		{
			$after_years_sep = ' ' . __(' and ') . ' ';
		}
		// 1, 0, 1
		elseif ($years > 0 AND $months == 0 AND $days > 0)
		{
			$after_years_sep = ' ' . __('and') . ' ';
		}
		// 0, 1, 1
		elseif ($years == 0 AND $months > 0 AND $days > 0)
		{
			$after_months_sep = ' ' . __('and') . ' ';
		}

		$result = $formatted_years . $after_years_sep . $formatted_months . $after_months_sep . $formatted_days;
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
  /*
  `period_start` et `period_end` sont des dates de références servant à définir un espace de temps.

  Lorsque `period_type` vaut `form`, `period_start` détermine le début de validité et `period_end` la fin de validité de la cotisation.
  Quand `period_type` vaut `due`, c'est `date_start`et `date_end` de la date de cotisation (`due`) qui determine la période de validité.

  Si `renewable` vaut FALSE, alors le bulletin d'adhésion deviendra inactif à la date d'échéance `period_dend`;


  Les rappel de cotisation avec VTODO et VALARM
*/
  /**
   * Date de début pour les factures
   *
   * @return String
   */
  public function date_start()
  {
    if ($this->period_type === 'form')
    {
      return $this->period_start;
    }

    // Due type
    return Date('Y-m-d');

  }

  public function date_end()
  {
    if ($this->period_type === 'form')
    {
      return $this->period_end;
    }

    // Due type
    return Date('Y-m-d', strtotime(Date('Y-m-d') . " + {$this->duration('days')} days"));
  }

  public function duration($output = "years,months,weeks,days,hours,minutes,seconds")
  {
    return Date::span(strtotime($this->period_start), strtotime($this->period_end), $output);
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
				'records_count' => NULL,
			);

			foreach ($this->dues->find_all() as $due)
			{
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
			$this->_currencies = $this->currencies->find_all_as_array($this->currency_code);
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
		$values['renewable'] = (bool) Arr::get($values, 'renewable', FALSE);
		$values['active'] = (bool) Arr::get($values, 'active', FALSE);

		return parent::values($values, $excepted);
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
    // $object['edit_url'] = $this->edit_url();
    $object['currency'] = $this->currency();
		// Periods stuffs
		$object['period_type_due'] = $this->period_type_due();
		$object['period_type_form'] = $this->period_type_form();
		$object['pretty_period_start'] = $this->pretty_period_start();
		$object['pretty_period_end'] = $this->pretty_period_end();
    $object['pretty_duration'] = $this->pretty_duration();
    // URLs
    $object['url'] = $this->url();
    $object['url_edit'] = $this->url_edit();

    $embed = $this->_embed($embed_paths);
		return array_merge($object, $embed);
	}

//------------- CLEAN THIS AREA ----------------//

	/**
	* Prefill somes defaults values
	**/
	public function __construct($id = NULL)
	{
		// /!\ ne pas utilisaer cast_data, pose des problème avec un find_all
		// Tester $this->_table_columns.gender.column_default
		// $this->_cast_data = array(
		// 	'period_type' => 'form',
		// 	'period_start' => date('Y', time()) . '-01-01',
		// 	'period_end' => date('Y', time()) + 1 . '-01-01',
		// 	'price' => '0.00',
		// 	'currency' => Kohana::$config->load('guidoline.currency.default'),
		// 	'free_price'  => TRUE,
		// 	'renewable'   => TRUE,
		// 	'active'      => TRUE,
		// );
		parent::__construct($id);
	}

	/**
	* Defaults values
	*
	* @param   string  $column  Column name
	* @throws  Kohana_Exception
	* @return  mixed
	**/
	public function get($column)
	{
		// Cf. ORM::$_table_columns[$column]['column_default']
		if ( ! $this->_loaded)
		{
			switch ($column) {
				case 'period_type':
					return 'form'; // Default database definition ?
					break;

				default:
					// continue;
					break;
			}
		}

		return parent::get($column);
	}

	/**
	* Set filters
	**/
	public function set($column, $value)
	{
		switch ($column) {
			case 'title':
				$this->slug = URL::title($value); // Do it wih filter
				break;
		}

		return parent::set($column, $value);
	}


}
