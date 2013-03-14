<?php

/**
* Le modèle de vue `View/User/Index.php` fournis les propriétés et méthodes pour le template `templates/user/index.mustache`.
* 
* @package    Guidoline
* @category   View model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Users_Index extends View_Layout {
	
	/**
	* @vars Title 	Le titre de la page
	**/
	public $title = "Guidoline - List all users";

	/**
	* @vars User_count	Le nombre d'utilisateurs retourné
	**/
	public $user_count;

	/**
	* Retourne la liste de tous les utilisateurs.
	*
	* @return 	Array 	Un tableau de tout les utilisateurs
	**/
	public function users()
	{
		$users = ORM::factory('user')->select(array('id', 'username', 'email'));
		$this->users_count = $users->count_all();
		return $users->find_all()->as_array();
	}

	/**
	* Retourne le nombre d'utilisateur de la dernière requête
	*/
	public function pouet()
	{
	}

}