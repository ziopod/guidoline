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


	public function get($column)
	{

		if ($column == 'created')
		{			
 			return mb_strtolower(strftime('%A %e %B %Y à %Hh%M', strtotime($this->_object['created'])));
		}

		return parent::get($column);
	}
}