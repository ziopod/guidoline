<?php defined('SYSPATH') or die ('No direct script access');

/**
 * Le modèle ORM pour la table "users"
 *
 * @todo      Reworke
 * @todo      Implementer le `as_array` de Guidoline API`
 * @todo      Implementer le système de jonction `embed` de Guidoline API
 * @package   Guidoline
 * @category  Model
 * @author    Ziopod | ziopod@gmail.com
 * @copyright BY-SA 2013 Ziopod
 * @license   http://creativecommons.org/licenses/by-sa/3.0/deed.fr
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

	/**
	* Règles de validation
	**/
	public function rules()
	{
		return array(
      'lastname' => array(array('not_empty')),
      'firstname' => array(array('not_empty')),
			'email' => array(
				array('not_empty'),
				array('min_length', array(':value', 4)),
				array('max_length', array(':value', 128)),
				array('email'),
				array(array($this, 'unique'), array('email', ':value')),
        //	array('already_exists', array(':validation', 'user', ':field'))
      ),
      'idm' => array(
        array(array($this, 'unique'), array('idm', ':value')),
      )
		);
	}

	/**
	* Labels
	**/
	public function labels()
	{
		return array(
			'email'	  	=> 'Addresse email',
			'lastname'	=> 'Nom de famille',
			'firstname'	=> 'Prénom',
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
      'birthdate' => array(
        array(function($value) {
          if ( ! $value)
          {
            return NULL;
          }
        })
      )
		);
  }

	/**
	* Extension de la méthode get
	**/
	// public function get($column)
	// {
	// 	switch ($column) {
	// 		case 'cellular' :
	// 			return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->_object['cellular']);
	// 			break;
	// 		case 'phone' :
	// 			return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->_object['phone']);
	// 			break;
	// 		case 'fancy_birthdate':
	// 			return ($this->birthdate !== NULL AND $this->birthdate !== '0000-00-00') ? date('Y', time()) - date('Y', strtotime($this->birthdate)) : NULL;
	// 			break;
	// 		case 'fancy_created':
	// 			return $this->created ? strftime('%e %b %Y', strtotime($this->created)) : FALSE;
	// 			break;
	// 		case 'fancy_gender':
	// 			return $this->gender === 'm' ? '&#9794' : '&#9792;';
	// 		default:
	// 			return parent::get($column);
	// 			break;
	// 	}
	// }
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
   *
   * Exploiter un trigger SQL pour inrémenter l'IDM
   */
	// public function create(Validation $validation = NULL)
	// {
	// 	// Création du idm
	// 	$idm = (int) ORM::factory('Member')
	// 		->select('idm')
	// 		->order_by('idm', 'DESC')
	// 		->limit(1)
	// 		->find()
	// 		->idm;
	// 	$this->idm = $idm + 1;

	// 	return parent::create($validation);
	// }

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

    // @todo : ajouter une méthode `Model_Subscriptions_Members::is_valid()`
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
	// public function last_subscription()
	// {

	// 	if ( ! $this->has('subscriptions', ORM::factory('subscription')))
	// 	{
	// 		return FALSE;
	// 	}

	// 	return $this->subscriptions_member->find();
	// }

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


	// /**
	// * Retourne la liste des genres et marque le genre du membre courant
	// **/
	// public function _genders()
	// {
	// 	$genders = array(
  //     'records_count' => 0, // field.size
  //     'records' => array(), // field.options
  //   );

	// 	foreach ($this->genders as $gender)
	// 	{
	// 		$genders['records'][] = array(
  //       'gender' => array( // option
  //         'value' 	=> $gender['value'],
  //         'label'		=> $gender['label'], // option.name
  //         'current'	=> $gender['value'] == $this->gender, // option.selected
  //       )
	// 		);
	// 	}

  //   $genders['records_count'] = count($genders['records']);
	// 	return $genders;
  // }

  /**
   * Localisation de la ressource
   *
   * @return String
   */
  public function url()
  {
    return Route::url('member', array('id' => $this->pk()));
  }

  /**
   * Nom complet
   *
   * @return String
   */
  public function fullname()
  {
    return $this->firstname . ' ' . $this->lastname;
  }

  /**
   * Wrapper pour l'adresse postale
   *
   * @return Array
   */
  public function address()
  {
    return array(
      'street' => $this->street,
      'zipcode' => $this->zipcode,
      'city' => $this->city,
      'country' => $this->country,
    );
  }

  /**
   * Adresse en jolie format
   *
   * @return String
   */
  public function pretty_address()
  {
    $address = $this->street ? $this->street . ",\n" : '';
    $address .= $this->zipcode ? $this->zipcode .' ': '';
    $address .= $this->city ? $this->city . ",\n" : '';
    $address .= $this->country ? $this->country : '';
    return $address;
  }

  /**
   * Les genres du membre
   *
   * Données associées
   *
   * @return Array
   */
  protected $_genders;

  public function genders()
  {
    if ( ! $this->_genders)
    {
      $this->_genders = $this->genders;
    }

    return $this->_genders;
  }

  /**
   * Liste des genre au format `select.options`
   *
   * Helper HTML Form
   *
   * @return Array
   */
  protected $_select_genders;

  public function select_genders()
  {
    if ( ! $this->_select_genders)
    {
      $this->_select_genders = array(
        'size' => 0,
        'options' => array(),
      );

      foreach ($this->genders() as $gender)
      {
        $this->_select_genders['options'][] = array(
          'option' => array(
            'value'     => $gender['value'],
            'label'     => $gender['label'],
            'selected'  => $gender['value'] === $this->gender,
          ),
        );
      }
    }

    $this->_select_genders['size'] = count($this->_select_genders['options']);
    return $this->_select_genders;
  }

  /**
   * Genre au format d'affichage
   *
   * @return String
   */
	public function fancy_gender()
	{
    foreach ($this->genders as $gender)
    {
      if ($gender['value'] === $this->gender)
      {
        return $gender['fancy'];
      }
    }

		return NULL;

	}

	public function fancy_birthdate()
	{
    if ($this->birthdate !== NULL AND $this->birthdate !== '0000-00-00')
    {
      return strftime('%e %B %Y', strtotime($this->birthdate));
    }

    return NULL;
  }

  public function age()
  {
    return date('Y', time()) - date('Y', strtotime($this->birthdate));
  }

	public function fancy_created()
	{
    return strftime('%e %B %Y', strtotime($this->created));
  }

  public function fancy_phone()
  {
    return preg_replace('/(\w{2})(\w{2})(\w{2})(\w{2})(\w{2})$/i', '$1 $2 $3 $4 $5',  $this->phone);

  }

	private function _format_infos($subscription)
	{

  }

  /**
   * Toutes les adhésions
   *
   * Celles d l'utilisateur actuel sont marquées comme courante.
   *
   * @return Array
   */
  protected $_subscriptions;

  public function subscriptions()
  {
    if ( ! $this->_subscriptions)
    {
      $this->_subscriptions = array(
        'records_count' => 0,
        'records' => array(),
      );

      // @todo : récupérer les clef id des adhésions valides

      // echo Debug::vars($this->last_valid_subscriptions()->as_array('id'));
      foreach (ORM::factory('Subscription')->order_by('created')->find_all() as $subscription)
      {
        $subscription_as_array = $subscription->as_array();
        // $subscription_as_array['current'] = $this->has('subscriptions', [$subscription->pk()]);
        $this->_subscriptions['records'][] = array(
          'subscription' => $subscription_as_array
        );
      }

      $this->_subscriptions['records_count'] = count($this->_subscriptions['records']);
      // echo Debug::vars($this->_subscriptions);
    }

    return $this->_subscriptions;
  }

  /**
   * Le prochain numéro d'adhérent
   *
   * @return Integer
   */
  public function next_idm()
  {

    return DB::query(Database::SELECT, 'SELECT max(`idm`) AS idm FROM members')
      ->execute()
      ->get('idm') + 1;
  }

  /**
	 * Les derniers membres crées
	 *
   * @param   Integer   Nombre d'entrées à retourner
	 * @return  Array
	 */

	public function last_created($limit = 10)
	{
    $last_created = array(
      'records_count' => 0,
      'records' => array(),
    );

    foreach (ORM::factory('Member')
      ->order_by('created', 'desc')
      ->limit($limit)
      ->find_All() as $member)
    {
      $last_created['records'][] = array(
        'member' => $member->as_array()
      );
    }

    $last_created['records_count'] = count($last_created['records']);
    return $last_created;

	}

  /**
   * Données safe
   *
   * Le template ne doit jamais recevoir l'objet de modèle complet.
   *
   * L'extension de cette méhode permet de retourner des
   * données sélectionnées en lecture seule.
   *
   * Ces données peuvent être personnalisées, ajoutées ou supprimées.
   *
   */
  public function as_array()
  {
    $data = parent::as_array();
    $data['fullname'] = $this->fullname();
    $data['address'] = $this->address();
    $data['url'] = $this->url();
    $data['pretty_address'] = $this->pretty_address();
    $data['fancy_gender'] = $this->fancy_gender();
    $data['fancy_phone'] = $this->fancy_phone();
    $data['fancy_birthdate'] = $this->fancy_birthdate();
    $data['first_subscription'] = $this->first_subscription();
    $data['age'] = $this->age();
    $data['fancy_created'] = $this->fancy_created();
    $data['select_genders'] = $this->select_genders();
    $data['next_idm'] = $this->pk() ? $this->idm : $this->next_idm();
    $data['is_loaded'] = $this->loaded();
    $data['subscriptions'] = $this->subscriptions();
    return $data;
  }

  /**
	 * Test si une valeur de clef est unique dans la base de données
	 *
   * @todo  À déplacer dans une classe parente (ORM)
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

}
