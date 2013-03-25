<?php defined('SYSPATH') or die ('No direct script access');

/**
 * Le modèle ORM pour la jointure "subscription/membre"
 *
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */
 
class Model_Subscriptions_Member extends ORM{

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
	
	/**
	* Date de création de l'adhésion
	*
	* @return	String	Date formatée
	*/
	public function start_date()
	{
		return $this->start_date;
	}

	/**
	* Date de fin d'adhésion
	*
	* @return	String	Date formaté
	**/
	public function end_date()
	{
		return $this->end_date;
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

		if ($column == 'start_date')
		{			
 			return strftime('%A %e %B %Y à %Hh%M', strtotime($this->_object['created']));
		}

		if ($column == 'end_date')
		{
			// Vrai nombres de jours dans l'année (date('z', mktime(0, 0, 0, 12, 31, Date::YEAR)) + 1 ) * 24 * 60 * 60; ou Date::YEAR
			return strftime('%A %e %B %Y à %Hh%M', (strtotime($this->created) + (int) $this->subscription->_object['expiry_time']));
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
			return strtotime($this->_object['created']) + (int) $this->subscription->_object['expiry_time'] > time() ? TRUE : FALSE;
		}

		return parent::get($column);
	}
}