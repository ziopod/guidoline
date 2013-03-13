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
 
class Model_Member extends ORM {
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
}