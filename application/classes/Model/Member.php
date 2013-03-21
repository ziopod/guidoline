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
		'subscription' => array(
			'model'		=> 'subscription',
			'through'	=> 'subscriptions_members'
		),
		'subscriptions_member' => array(
			'model'		=> 'subscriptions_member',
		),
	);

	/**
	* Règles de validation
	**/
	public function rules()
	{
		return array(
			'email' => array(
				array('not_empty'),
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
	* Indique des informations sur la dernière date d'adhésion
	*
	* @return	Boolean
	**/
	public function last_subscription()
	{
		//var_dump('pouet');
		if ( ! $this->has('subscription'))
		{
			return FALSE;
		}

		//echo Debug::vars(strftime('%A %e %B %Y à %H:%M:%S', time()));
		$true_seconds_in_current_year = (date('z', mktime(0, 0, 0, 12, 31, Date::YEAR)) + 1 ) * 24 * 60 * 60;
		//echo Debug::vars(date('z', mktime(0,0,0,12,31,date("Y", time()))) +1 );
	//	echo Debug::vars($true_seconds_in_current_year);
//		31104000
//		 mktime(0,0,0,12,31,2008)
		
		// (cal_days_in_month(CAL_GREGORIAN, 2, date("Y", time())) === 29) ? true : false; // Année bissextile (is leap year)
		// (Date::days(2, date("Y", time()) === 29); //  Utilise Mktime
		$last_subscription = $this->subscriptions_member->find();
		$subscription = $this->subscription->find();
		$expiry_time = $true_seconds_in_current_year; //$subscription->expiry_time;
		$start_date = $last_subscription->created;
		$start_time = strtotime($start_date);
		$end_date = strftime('%A %e %B %Y à %Hh%M', $start_time + $expiry_time);
		$date_infos = array(
			'valid'					=> $start_time + $expiry_time > time() ? TRUE : FALSE,
			'subscription'			=> $subscription,
			'start_date'			=> $start_date,
			'end_date'				=> $end_date,
			'date_span'				=> Date::span($start_time),
			'date_span_fuzzy'		=> Date::fuzzy_span($start_time),
			'date_remaining'		=> Date::span($start_time + $expiry_time),
			'date_remaining_fuzzy'	=> Date::fuzzy_span($start_time + $expiry_time),
		);
		return $date_infos;
	}

	/**
	* Indique si la dernière inscirption est toujours valide
	**/
	public function valid_subscription()
	{

		$subscription_date = strtotime($this->last_subscription()->created);
		return array(
			//'date'	=>  strtotime('', 'subscription_date');
			'fuzzy' => Date::fuzzy_span($subscription_date, time()),
			);
	}
}