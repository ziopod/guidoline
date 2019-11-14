<?php defined('SYSPATH') or die ('No direct script access');

/**
 * Le modèle ORM pour la table "users"
 *
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */
 
class Model_Member extends ORM{

	/**
	* Ordre de trie par défaut
	**/
	protected $_sorting = array(
		'idm'	=> 'DESC',
	);

	/**
	* Colonne de date de creation
	**/
	protected $_created_column = 'created';
	
	/**
	* Colone de date de mise à jour
	**/
	protected $_updated_column = 'updated';

	/**
	* Relationship
	**/
	protected $_belongs_to = array(
		'user'	=> array(
			'model'	=> 'User',
		),
		'status'	=> array(
			'model'	=> 'Status',
		),
	);

	protected $_has_many = array(
		'subscriptions' => array(
			'model'		=> 'Subscription',
			'through'	=> 'subscriptions_members'
		),
		'subscriptions_members' => array(
			'model'		=> 'Subscriptions_Member',
		),
	);
	
	public $genders = array(
		array(
			'value' => null,
			'label' => 'n/a'
		),
		array(
			'value'	=> 'm',
			'label'	=> 'Homme',
		),
		array(
			'value'	=> 'f',
			'label'	=> 'Femme',
		)
	);

	public $titles = array(
			array(
				'value'	=> 'm.',
				'label'	=> 'M.',
			),
			array(
				'value'	=> 'mm.',
				'label'	=> 'Mm.',
			),
			array(
				'value'	=> 'mlle',
				'label'	=> 'Mlle',
			),
		);


	/**
	* Règles de validation
	**/
	public function rules()
	{
		return array(
			'email' => array(
//				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 128)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
			//	array('already_exists', array(':validation', 'user', ':field'))
			),
		);
	}

	/**
	* Labels
	**/
	public function labels()
	{
		return array(
			'email'		=> 'Email',
		);
	}

	/**
	* Filtres pour les données de formulaires
	**/
	public function filters()
	{
		return array(
			'email' => array(
				array('trim', array(':value')),
			),
		);
	}

	/**
	* Test si une valeur de clef est unique dans la base de données
	*
	* @param	mixte	valeur à tester
	* @param	string	field name
	* @return	boolean
	*/
	public function unique_key_exists($value, $field = NULL)
	{
		if ($field === NULL)
		{
			$field = $this->unique_key($value);
		}

		return (bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where($field, '=', $value)
			->where($this->_primary_key, '!=', $this->pk())
			->execute($this->_db)
			->get_('total_count');
	}

	/**
	* Extension de la méthode get
	**/
	public function get($column)
	{
		switch ($column) {
			case 'cellular' :
				return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->_object['cellular']);
				break;
			case 'phone' :	
				return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->_object['phone']);
				break;
			case 'fancy_birthdate':
				return ($this->birthdate !== NULL AND $this->birthdate !== '0000-00-00') ? date('Y', time()) - date('Y', strtotime($this->birthdate)) : NULL;
				break;
			case 'fancy_created':
				return $this->created ? strftime('%e %b %Y', strtotime($this->created)) : FALSE;
				break;
			case 'fancy_gender':
				return $this->gender === 'm' ? '&#9794' : '&#9792;';
			default:
				return parent::get($column);
				break;
		}

	}
	/**
	* Retourne la liste des statut disponibles et "marque" le status courant
	*
	* @return Object
	**/

	public function statuses()
	{
		$statuses = DB::select('id', 'name')->from('statuses')->execute()->as_array();
		$current_status_key = array_search(
			array(
				'id'=>$this->status->id,
				'name' => $this->status->name
			), $statuses);
		$statuses[$current_status_key]['selected'] = TRUE;
		return $statuses;
	}

	/**
	* Extension de la méthode native create
	**/
	public function create(Validation $validation = NULL)
	{
		// Création du idm
		$idm = (int) ORM::factory('Member')
			->select('idm')
			->order_by('idm', 'DESC')
			->limit(1)
			->find()
			->idm;
		$this->idm = $idm + 1;

		return parent::create($validation);
	}

	/**
	* Retourne les dernière adhésion valides
	*
	* @return	Mixte	False ou tableau des dernières adhésions valides
	**/
	public function last_valid_subscriptions()
	{
		if ( ! $this->has('subscriptions'))
		{
			return array();
		}

		// AND DATE_ADD(`subscriptions_member`.`created`, INTERVAL `subscription`.`expiry_time` SECOND) >  CURDATE()
		// AND TO_SECONDS(`subscriptions_member`.`created`) + `subscription`.`expiry_time` >  TO_SECONDS(NOW())

		$lasts = $this->subscriptions_members
			->with('subscription')
//			->where(DB::expr("TO_SECONDS(`subscriptions_member`.`created`) + `subscription`.`expiry_time`"), '>', DB::expr("TO_SECONDS(NOW())"))
	//					$expired = Date::span($created, strtotime((date('Y', time()) + 1) . '-01-01'), 'months') > 2;

			->where(DB::expr("TO_SECONDS(`subscriptions_member`.`created`) + `subscription`.`expiry_time`"), '>', DB::expr("TO_SECONDS(NOW())"))
			->find_all();

		if ( ! count($lasts))
		{
			return array();
		}
		
		return $lasts;
	}

	/**
	* Retourne la dernière adhésion (informations de dates formatés)
	*
	* @return	Mixte	False ou tableau contenant la dernière adhésion
	**/
	public function last_subscription()
	{

		if ( ! $this->has('subscriptions', ORM::factory('subscription')))
		{
			return FALSE;
		}
		
		return $this->subscriptions_member->find();
	}

	/**
	* Retourne la première adhésion
	*
	* @return	Mixte	False ou tableau contenant la première adhésion
	**/
	public function first_subscription()
	{
		if ( ! $this->has('subscriptions'))
		{
			return FALSE;
		}

		return $this->subscriptions_members->order_by('created', 'ASC')->find();
	}

	/**
	* Retourne la liste des genres et marque le genre du membre courant
	**/
	public function genders()
	{
		$genders = array();

		foreach ($this->genders as $gender)
		{
			$genders[] = array(
				'value' 	=> $gender['value'],
				'label'		=> $gender['label'],
				'current'	=> $gender['value'] == $this->gender,
			);
		}

		return $genders;
	}

	//pubic function
	public function fancy_gender()
	{
		return $this->fancy_gender;

	}

	public function fancy_birthdate()
	{
		return $this->fancy_birthdate;
	}

	public function fancy_created()
	{
		return $this->fancy_created;
	}
	
	private function _format_infos($subscription)
	{

	}
}