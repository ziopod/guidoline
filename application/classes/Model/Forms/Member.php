<?php defined('SYSPATH') OR die('No direct script access');

/**
 * Le modèle ORM pour la jointure "subscription/membre"
 *
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */

class Model_Forms_Member extends ORM{

	/**
	* Ordre de trie par défaut
	**/
	protected $_sorting = array(
		'created'	=> 'DESC',
	);

	/**
	* Relationship
	**/
	protected $_belongs_to = array(
		'subscription' => array(
			'model'		=> 'Subscription',
		),
	);

	public function __construct($id = NULL)
	{
		parent::__construct();

		if ( ! $this->loaded())
		{
			$this->start_date = date('Y-m-d');
		}
	}

	public function save(Validation $validation = NULL)
	{
		$this->end_date = Model_Subscription::estimated_end_date($this->subscription->duration, $this->start_date);
		parent::save();
	}

	/**
	* Pretty end date
	*
	* @return string
	**/
	public function pretty_end_date()
	{
		return strftime('%B %G', strtotime($this->end_date));
	}

	/**
	* Date de fin d'adhésion au format flou
	*
	* @return	String	Date formaté au foramt flou
	**/
	public function end_date_fuzzy()
	{
		return $this->end_date_fuzzy;
	}

	/**
	* Temps écoulé depuis la création de l'adhésion
	*
	* @return	Array	Tableau dont les index sont réspectivement les années, les mois, les jours, les heures et les secondes
	**/
	public function elapsed_time()
	{
		return $this->elapsed_time;
	}

	/**
	* Temps écoulé depuis la création de l'adhésion dans un format flou
	*
	* @return	String	Date au foramt flou
	**/
	public function elapsed_time_fuzzy()
	{
		return $this->elapsed_time_fuzzy;
	}

	/**
	* Temps restant avant la fin de l'adhésion
	*
	* @return	Array	Tableau dont les index sont réspectivement les années, les mois, les jours, les heures et les secondes
	**/
	public function remaining_time()
	{
		return $this->remaining_time;
	}

	/**
	* Temps restant avant la fin de l'adhésion dans un format flou
	*
	* @return	String	Date au format flou
	**/
	public function remaining_time_fuzzy()
	{
		return $this->remaining_time_fuzzy;
	}

	/**
	* L'adhésion est-elle toujours valide
	*
	* @return	Boolean
	**/
	public function valid_subscription()
	{
		return $this->valid_subscription;
	}

	public function get($column)
	{


		if ($column == 'end_date_fuzzy')
		{
			// Vrai nombres de jours dans l'année (date('z', mktime(0, 0, 0, 12, 31, Date::YEAR)) + 1 ) * 24 * 60 * 60; ou Date::YEAR
			return Date::fuzzy_span(strtotime($this->created) + (int) $this->subscription->_object['expiry_time']);
		}

		if ($column == 'elapsed_time')
		{
			return Date::formatted_span(strtotime($this->_object['created']));
		}

		if ($column == 'elapsed_time_fuzzy')
		{
			return Date::fuzzy_span(strtotime($this->_object['created']));
		}

		if ($column == 'remaining_time')
		{
			return Date::formatted_span(strtotime($this->_object['created']) + (int) $this->subscription->_object['expiry_time'], time(), 'years,months,weeks,days');
		}

		if ($column == 'remaining_time_fuzzy')
		{
			return Date::fuzzy_span(strtotime($this->_object['created']) + (int) $this->subscription->_object['expiry_time']);
		}

		if ($column == 'valid_subscription')
		{
			$created = (int) strtotime($this->_object['created']);
			//$expiry_time = (int) $this->subscription->_object['expiry_time'];
			//return Date::span($created, strtotime((date('Y', time()) +1 ) . '-01-01'), 'months');
			//$expired = $created + $expiry_time  > time() ? TRUE : FALSE;
			//return strtotime('2013-02-02'). '  '. strtotime(date('Y', time()) . '-01-01');
			//return Date::span($created, strtotime((date('Y', time()) +1) . '-01-01'), 'months');
			return Date::span($created, strtotime((date('Y', time()) +1) . '-01-01'), 'months') < 15; // Orignal
			//return $this->_object['created'];
			//return date('Y', time()) + 1 . '-01-01';
//			$expired = Date::span($created, strtotime((date('Y', time()) + 1) . '-01-01'), 'months') < 2;

			//return $expired;
		}

		return parent::get($column);
	}
}
