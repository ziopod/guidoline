<?php

/**
* Le modèle de vue `View/User/Index.php` fournis les propriétés et méthodes pour le template `templates/user/index.mustache`.
* 
* @package    Guidoline
* @category   View model
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_User_Index extends View_Layout {
	
	/**
	* Le titre de la page
	**/
	public $title = "Guidoline — Users";

	/**
	* Propriété hébergant le résultat de requête "users"
	**/
	public $users;

	/**
	* Récuperons les utilisateurs
	**/
	public function users()
	{
		//return $this->users->as_array(); /* Une fois la DB en place */
		return $this->users;

	}

}