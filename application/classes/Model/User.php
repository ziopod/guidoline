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
 
class Model_User extends Model_Auth_User {

	/**
	* Relationship
	**/
	protected $_has_one = array(
		'member'	=> array(
			'model' => 'Member',
		),
	);
	/**
	* Le plus haut niveau de rôle de l'utilisateur
	**/
	public $role;

	/**
	 * Handles garbage collection and deleting of expired objects.
	 *
	 * @return  void
	 */
	public function __construct($id = NULL)
	{
		parent::__construct($id);

		if ($this->loaded())
		{
			$roles = $this->roles->find_all()->as_array();
			$roles = array_reverse($roles);
			$this->role = current($roles);
		}
	}


	/**
	* Règles de validation
	**/
	// public function rules()
	// {
	// 	return array(
	// 		'username' => array(
	// 			array('not_empty'),
	// 			array('min_length', array(':value', 4)),
	// 			array('max_length', array(':value', 32)),
	// 			array('regex', array(':value', '/^[-\pL\pN_.]++$/uD')),
	// 			array('already_exists', array(':validation', 'user', ':field'))
	// 		),
	// 		'email' => array(
	// 			array('not_empty'),
	// 			array('min_length', array(':value', 4)),
	// 			array('max_length', array(':value', 128)),
	// 			array('already_exists', array(':validation', 'user', ':field'))
	// 		),
	// 	);
	// }

	/**
	* Labels
	**/
	// public function labels()
	// {
	// 	return array(
	// 		'email'		=> 'Email',
	// 		'username'	=> 'Username',
	// 	);
	// }

	/**
	* Filtres pour les données de formulaires
	**/
	// public function filters()
	// {
	// 	return array(
	// 		'username' => array(
	// 			array('trim', array(':value')),
	// 		),
	// 	);
	// }
}