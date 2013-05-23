<?php defined('SYSPATH') or die ('No direct script access');

/**
 * Le modèle ORM pour la table "subscription"
 *
* @package    Guidoline
* @category   Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */
 
class Model_Subscription extends ORM{

	/**
	* Ordre de trie par défaut
	**/
	protected $_sorting = array(
		'created'	=> 'DESC',
	);

	/**
	* Règles de validation
	**/
	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
			),
		);
	}

	public function get($column)
	{

		if ($column == 'created')
		{			
 			return strftime('%A %e %B %Y à %Hh%M', strtotime($this->_object['created']));
		}

		if ($column == 'expiry_time')
		{
			return Date::formatted_span($this->_object['expiry_time'], $this->_object['expiry_time'] * 2);
		}

		return parent::get($column);
	}
}