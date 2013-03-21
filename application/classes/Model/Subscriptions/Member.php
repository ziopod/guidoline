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
			'model'		=> 'subscription',
		),
	);
}