<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Member model
 *
 * @package    Guidoline
 * @category   Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Member extends ORM {

	/**
	 * @var Array  Belongs to relationships
	 */
	protected $_belongs_to = array(
		'user'	=> array(
			'model'	=> 'User',
		),
	);

	/**
	 * @var Array  Has many relationships
	 */
	protected $_has_many = array(
		'forms' => array(
      'through' => 'dues',
		),
		'dues' => array(),
		'skills' => array(
			'through' => 'members_skills'
		),
	);

	/**
	 * @var Array  Default sorting order
	 */
	protected $_sorting = array(
		'id'	=> 'desc',
	);

	/**
	 * @var Array  Created column
	 */
	protected $_created_column = array(
		'column' => 'created',
		'format' => 'Y-m-d h:i:s',
	);

	/**
	 * @var Array  Updated column
	 */
	protected $_updated_column = array(
		'column' => 'updated',
		'format' => 'Y-m-d h:i:s',
	);

  /**
   * @var ORM Active dues query
   */
  protected $_orm_dues;

  /**
	 * @var Array  Active dues storage
	 */
	protected $_dues;

  /**
	 * @var Array  All dues storage
	 */
	protected $_dues_all;

	/**
	 * @var Array  Membership skills storage
	 */
	protected $_skills;

	/**
	 * @var Array  All skills storage
	 */
  protected $_skills_all;

  /**
   * @var Array   All member forms storage
   */
  protected $_forms_all;

  /**
   * @var Array $_forms   Active Members Forms storage
   */
  protected $_forms;

  /**
	 * Validation rules
	 *
	 * @return Array
	 */
	public function rules()
	{
		return array(
			'firstname' => array(array('not_empty')),
			'lastname' => array(array('not_empty')),
      'birthdate' => array(array('regex', array(':value', '/[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])/'))),
			'email' => array(
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 128)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
      ),
      'idm' => array(
        array(array($this, 'unique'), array('idm', ':value')),
      )
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
			'birthdate' => array(
				array(function($value)
				{
					// Do a true real NULL value
					if ($value === '0000-00-00' OR $value == NULL)
					{
						return NULL;
					}

					return $value;
				}),
      ),
      'firstname' => array(
        array('ORM::case_title'),
      ),
      'lastname' => array(
        array('ORM::case_title'),
      ),
      'email' => array(
        array('trim'),
        array('strtolower'),
        array('ORM::nullish'),
      ),
      'gender' => array(array('ORM::nullish')),
      'street' => array(
        array('trim'),
        array('ORM::nullish'),
      ),
      'zipcode' => array(
        array('trim'),
        array('ORM::nullish'),
      ),
      'city' => array(
        array('trim'),
        array('mb_strtoupper'),
        array(function($value) {
          return str_replace(' ', '-', $value);
        }),
        array('ORM::nullish'),
      ),
      'phone' => array(
        array(function($value) {
          return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5', $value);
        }),
        array('ORM::nullish'),
      ),
		);
	}

	/**
	 * Labels map for column name
	 *
	 * Notably use for message errors
	 *
	 * @return Array
	 */
	public function labels()
	{
		return array(
			'email'	  	=> 'Addresse email',
			'lastname'	=> 'Nom de famille',
			'firstname'	=> 'Prénom',
      'idm'	      => 'Numéro d\'adhérent',
      'birthdate' => 'Date de naissance',
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
			'user' => array(
				'id',
				'email',
				'username',
				'role',
			),
			'skills'     => 'skills',
			'skills_all' => 'skills_all',
      'dues' => 'dues',
			'dues_all' => 'dues_all',
      'genders' => 'genders',
      'forms_all' => 'forms_all',
      'forms' => 'forms',
      'address' => 'address',
      'raw_address' => 'raw_address',
      'age' => 'age',
      'pretty_birthdate' => 'pretty_birthdate',
      'pretty_created' => 'pretty_created',
      'pretty_updated' => 'pretty_updated',
      'pretty_gender' => 'pretty_gender',
      'url_picture' => 'url_picture',
		);
	}

	/**
	 * Member card URL
 	 *
	 * @return String
	 */
	public function url()
	{
    if ( ! $this->loaded())
    {
      return NULL;
    }

		return URL::site(Route::get('member.detail')->uri(array('member_id' => $this->pk())), TRUE);
	}

	/**
	 * Member edit URL
	 *
	 * @return String
	 */
	public function url_edit()
	{
		return URL::site(Route::get('member.edit')->uri(array('member_id' => $this->pk())), TRUE);
  }

  /**
   * Profil pricture URL
   *
   * @return String
   */
  public function url_picture()
  {
    return "https://www.gravatar.com/avatar/" . md5($this->email) . "?d=identicon&s=65";
  }

	/**
	 * Compiled firstname + lastname
	 *
	 * @return String|Boolean
	 */
	public function fullname()
	{
    $fullname = trim($this->firstname . ' ' . $this->lastname);
    return $fullname ? $fullname : $this->pk();
  }


  /**
   * Address or NULL
   *
   * @return Array|NULL
   */
  public function address()
  {
    $address = array(
      'street' => $this->street,
      'zipcode' => $this->zipcode,
      'city'  => $this->city,
      'country' => $this->country,
    );

    return array_filter($address, function($value) {return ! is_null($value) && $value !== ''; });
  }

  /**
   * Pretty format address
   */
  public function raw_address()
  {
    $raw_address = "";

    $raw_address .= $this->fullname() ? "**{$this->fullname()}**\n" : '';
    $raw_address .= $this->street ? "{$this->street}\n" : '';
    $raw_address .= $this->zipcode ? "{$this->zipcode} " : '';
    $raw_address .= $this->city ? "{$this->city}\n" : '';
    $raw_address .= $this->country ? Arr::get($this->_country_code_map(), $this->country) . "\n" : '';

    return $raw_address;
  }

  protected function _country_code_map()
  {
    return array(
      'FR'  => 'France',
      'JP'  => 'Japon',
      'DE'  => 'Allemagne'
    );
  }

	/**
	 * Phone in pretty format
	 *
	 * @return String
	 */
	// public function pretty_phone()
	// {
  //   if ($this->phone === NULL)
  //   {
  //     return NULL;
  //   }

	// 	return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->phone);
	// }

	/**
	 * Birthdate in pretty format
	 *
	 * @return String
	 */
	public function pretty_birthdate()
	{
    if ($this->birthdate === NULL)
    {
      return NULL;
    }

		return $this->birthdate ? strftime(__('date.long'), strtotime($this->birthdate)) : NULL;
	}

	/**
	 * Age
	 *
	 * @return Integer
	 */
	public function age()
	{
    if ($this->birthdate === NULL)
    {
      return NULL;
    }

		return Date::span(strtotime($this->birthdate), time(), 'years');
	}

	/**
	 * Created in pretty format
	 *
	 * @return String
	 */
	public function pretty_created()
	{
		return $this->created ? strftime(__('date.long'), strtotime($this->created)) : NULL;
	}

	/**
	 * Updated in pretty format
	 *
	 * @return String
	 */
	public function pretty_updated()
	{
		return $this->updated ? strftime(__('date.long'), strtotime($this->updated)) : NULL;
  }

  /**
   * Gender in pretty format
   */
  public function pretty_gender()
  {
    return $this->gender ? __('gender.pretty.' . $this->gender) : NULL;
  }
  /**
   * Kind of garbage collector for `is_active`
	 *
	 * @param   string $column Column name
	 * @throws Kohana_Exception
	 * @return mixed
   */
  public function get($column)
  {
    if ($column === 'is_active')
    {

      if ($this->_object['is_active'] == 1)
      {
        // Count valid dues
        $valid_dues = DB::select(DB::expr('COUNT(*) as count'))
        ->from('dues')
        ->where('member_id', '=', $this->pk())
        ->and_where(DB::expr('DATE(`date_end`)'), '>=', date('Y-m-d'))
        ->execute()->get('count') > 0;
        // Save is_active state
        if ( ! $valid_dues)
        {
          DB::update($this->_table_name)
          ->set(array('is_active' => 0))
          ->where('id', '=', $this->pk())
          ->execute();
          return 0;
        }
      }
    }

    return parent::get($column);
  }

	/**
	 * Member actives dues
	 *
	 * @return Array
	 */
	public function dues()
	{
    // If member is unactive, no dues are possible
    if ( ! $this->is_active)
    {
			$this->_dues = array(
				'records' => array(),
				'records_count' => 0,
      );
    }

		if ( ! $this->_dues)
		{
			$this->_dues = array(
				'records' => array(),
				'records_count' => NULL,
			);

			foreach ($this->_orm_dues()->where('date_end', '>', date('y-m-d'))->find_all() as $due)
			{
				$this->_dues['records'][]['due'] = $due->as_array('currency');
			}

      $this->_dues['records_count'] = count($this->_dues['records']);
		}

		return $this->_dues;
	}

  /**
   * Active dues query
   *
   * @return ORM
   */
  protected function _orm_dues()
  {
    if ( ! $this->_orm_dues)
    {
      $this->_orm_dues = $this->dues;
    }

    return $this->_orm_dues;
  }

	/**
	 * All member dues
	 *
	 * For history purpose
	 *
	 * @return Array
	 */
	public function dues_all()
	{
		if ( ! $this->_dues_all)
		{
			$this->_dues_all = array(
				'records' => array(),
				'records_count' => NULL,
			);

			foreach ($this->dues->order_by('created', 'desc')->find_all() as $due)
			{
				$due = $due->as_array();
				$this->_dues_all['records'][]['due'] = $due;
			}

			$this->_dues_all['records_count'] = count($this->_dues_all['records']);
		}

		return $this->_dues_all;
	}

	/**
	 * All related skills
	 *
	 * Find all related member skills and put it array
	 *
	 * @return Array
	 */
	public function skills()
	{
		if ( ! $this->_skills)
		{
			$this->_skills= array(
				'records'       => array(),
				'records_count' => NULL,
			);

			foreach ($this->skills->find_all() as $skill)
			{
				$this->_skills['records'][]['skill'] = $skill->as_array();
			}

			$this->_skills['records_count'] = count($this->_skills['records']);
		}

		return $this->_skills;
	}

	/**
	 * Find all skills and set member skills as current
	 *
	 * @return array
	 */
	public function skills_all()
	{
		if ( ! $this->_skills_all)
		{
			$this->_skills_all = array(
				'records'       => array(),
				'records_count' => NULL,
			);

			$current_skills = $this->skills->find_all()->as_array('id', 'name');

			foreach (ORM::factory('Skill')->find_all() as $skill)
			{
				$skill = $skill->as_array();
				$skill['current'] = isset($current_skills[$skill['id']]);
				$this->_skills_all['records'][]['skill'] = $skill;
			}

			$this->_skills_all['records_count'] = count($this->_skills_all['records']);
		}

		return $this->_skills_all;
  }

  /**
   * Find all member forms and set current member form as current
   *
   * @return Array
   */

  public function forms_all()
  {
    if (! $this->_forms_all)
    {
      $this->_forms_all = array(
        'records'       => array(),
        'records_count' => NULL,
      );

      $current_forms = DB::select('form_id')
      ->distinct(TRUE)
      ->from('dues')
      ->where('member_id', '=', $this->pk())
      ->execute();

      $current_forms = Arr::pluck($current_forms->as_array(), 'form_id');

      foreach (ORM::factory('Form')
        ->where('is_active', '=', 1)
        ->find_all() as $member_form)
      {
        $member_form = $member_form->as_array();
        $member_form['current'] = in_array($member_form['id'], $current_forms);
        $member_form['disabled'] = $member_form['current'] && ! $member_form['is_renewable'] ? 'disabled' : '';
        $this->_forms_all['records'][]['member_form'] = $member_form;
      };

      $this->_forms_all['records_count'] = count($this->_forms_all['records']);
    }

    return $this->_forms_all;
  }

  /**
   * Members actives Forms
   *
   * @return Array
   */
  public function forms()
  {
    if ( ! $this->_forms)
    {
      $this->_forms = array(
        'records' => array(),
        'records_count' => 0
      );

      foreach ($this->forms_all()['records'] as $form)
      {

        if ($form['member_form']['current'] === TRUE)
        {

          $form_dues = array_filter(
            $this->dues()['records'],
            function($due) use($form)
            {
              // echo Debug::vars($form);
              if ($due['due']['form_id'] != $form['member_form']['id'])
              {
                return FALSE;
              }
              return $due;
            }
          );
          // Patch : Reset des clefs des tableau pour éviter une erreur
          // d'interprétation de Mustache PHP
          $form_dues = array_values($form_dues);

          $form['member_form']['dues']['records'] = $form_dues;
          $form['member_form']['dues']['records_count'] = count($form_dues);
          $this->_forms['records'][] = $form;
        }
      }

      $this->_forms['records_count'] = count($this->_forms['records']);

    }

    return $this->_forms;
  }



  /**
   * Data mapping for genders
   *
   * Trick to avoid using relationnal table data for simple set of data.
   *
   * @return Array
   */
  protected function _mappings_genders()
  {
    return array(
      array(
        'value' => null,
        'label' => 'n/a',
        'fancy' => 'n/a',
      ),
      array(
        'value'	=> 'm',
        'label'	=> 'Homme',
        'fancy'	=> '&#9794',
      ),
      array(
        'value'	=> 'f',
        'label'	=> 'Femme',
        'fancy'	=> '&#9792;',
      )
    );
  }

  /**
   * Find all genders and set membership gender as current
   *
   * @return Array
   */
  protected $_genders;

  public function genders()
  {
    if ( ! $this->_genders)
    {
      $this->_genders = array(
        'records'       => array(),
        'records_count' => NULL,
      );

      foreach ($this->_mappings_genders() as $gender)
      {
        $gender['current'] = $this->gender === $gender['value'];
        $this->_genders['records'][]['gender'] = $gender;
      }

      $this->_genders['records_count'] = count($this->_genders['records']);
    }

    return $this->_genders;
  }

  /**
   * Active Member
   */
  protected function _is_active()
  {
    $active = $this->is_active;

    if ($active == 0)
    {
      return FALSE;
    }
    // Test eligibility
    if ($this->_orm_dues()->count_all() < 1)
    {
      $this->is_active = 0;
      $this->save();
    }

    return $this->is_active;
  }

  /**
   * Next ID Member
   *
   * Find the next missing IDM
   *
   * Or for ignoring missing IDMs and just find the next one, use :
   * ~~~
   * DB::query(Database::SELECT, 'SELECT max(`idm`) AS idm FROM members')
   *   ->execute()
   *   ->get('idm') + 1;
   * ~~~
   *
   * @return Integer
   */
  public function next_idm()
  {
    $next_missing_idms = $this->_next_missing_idms();
    return array_shift($next_missing_idms);
  }

  /**
   * Next mising IDMs
   *
   * @return Array
   */
  protected function _next_missing_idms()
  {
		$next_missing_idms = DB::select(array(DB::expr('`idm` + 1'), 'next_missing_idm'))
			->from($this->_table_name)
			->where(NULL, 'NOT EXISTS',
				DB::select('idm')
				->from(array($this->_table_name, 'members_bis'))
				->where('members_bis.idm', '=', DB::expr("`{$this->_table_name}`.`idm` + 1")));
    $next_missing_idms = array_keys($next_missing_idms->execute()->as_array('next_missing_idm'));

		return count($next_missing_idms) === 0 ? array(1) : $next_missing_idms;
  }

  /**
   * Populate HTML form
   *
   * @param   Array   $errors   ORM Validation exception errors
   * @return  Array
   */
  public function html_form($errors = array())
  {
    return  array(
      'action' => $this->url_edit() . '#form_member',
      'member_id' => $this->pk(),
      'idm' => $this->idm,
      // 'notifications' => array(
      //     array('notification' => array(
      //       'kind' => 'warning', // `info | sucess | warning | danger`
      //       'content' => "Exemple de message",
      //       'deletable' => TRUE,
      //     )),
      //     array('notification' => array(
      //       'kind' => 'danger', // `info | success | warning | danger`
      //       'content' => "Attention !",
      //     )),
      // ),
      'data' => array(
        // Identité
        'lastname' => array(
          'field' => array(
            'label' => 'Nom',
            'name'  => 'lastname',
            'id'    => 'lastname',
            'value' => $this->lastname,
            'error' => Arr::get($errors, 'lastname'),
            'required' => 'required',
            )
          ),
          'firstname' => array(
            'field' => array(
              'label' => 'Prénom',
              'name'  => 'firstname',
              'id'    => 'firstname',
              'value' => $this->firstname,
              'error' => Arr::get($errors, 'firstname'),
              'required' => 'required',
          )
        ),
        'birthdate' => array(
          'field' => array(
            'label' => 'Date de naissance',
            'name'  => 'birthdate',
            'id'    => 'birthdate',
            'value' => $this->birthdate,
          )
        ),
        'select_genders' => array(
          'field' => array(
            'label'   => 'Genre',
            'name'    => 'gender',
            'id'      => 'gender',
            'options' => $this->records_to_options($this->genders()),
          ),
        ),
        // Contact
        'email' => array(
          'field' => array(
            'label' => 'Addresse email',
            'name'  =>  'email',
            'id'    => 'email',
            'value' => $this->email,
            'error' => Arr::get($errors, 'email'),
          ),
        ),
        'phone' => array(
          'field' => array(
            'label' => 'Téléphone',
            'name'  =>  'phone',
            'id'    => 'phone',
            'value' => $this->phone,
          ),
        ),
        // Addresse
        'address' => array(
          'street' => array('field' => array(
            'label' => 'Rue',
            'name'  => 'address[street]',
            'id'    => 'address-street',
            'value' => $this->street, // $this->member()['address']['street']
          )),
          'zipcode' => array('field' => array(
            'label' => 'Code postal',
            'name'  => 'address[zipcode]',
            'id'    => 'address-zipcode',
            'value' => $this->zipcode,
          )),
          'city' => array('field' => array(
            'label' => 'Ville',
            'name'  => 'address[city]',
            'id'    => 'address-city',
            'value' => $this->city,
          )),
          'country' => array('field' => array(
            'label' => 'Pays',
            'name'  => 'address[country]',
            'id'    => 'address-country',
            'value' => $this->country,
          ))
        ),
      ),
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
		// Raw from database
		$data = parent::as_array();
		// Load state
    $data['loaded'] = $this->loaded();
    // IDM
    $data['idm'] = $this->pk() ? $this->idm : $this->next_idm();
		// Identity
		$data['fullname'] = $this->fullname();
		// $data['pretty_birthdate'] = $this->pretty_birthdate();
		// $data['age'] = $this->age();
		// URL
		$data['url'] = $this->url();
		$data['url_edit'] = $this->url_edit();
		// Dates
		// $data['pretty_created'] = $this->pretty_created();
    // $data['pretty_updated'] = $this->pretty_updated();
		// Embeded values
    $embed = $this->_embed($embed_paths);
		return array_merge($data, $embed);
	}

}
