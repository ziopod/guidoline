<?php

/**
* Le modèle de vue `View/Dashboard.php` fournis les propriétés et méthodes pour le template `templates/dashboard.mustache`.
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/


class View_Dashboard extends View_Layout {
	
	/**
	* @vars Title Titre pour le dashboard
	*/
	public $title = "Guidoline — Dashboard";

	/**
	* Test de connexion à la base de données
	**/
	public function db_connection_test()
	{
		 try
		 {
		 	Database::instance()->connect();
		 	return "— Connexion ok —";
		 }
		 catch(Exception $e)
		 {
		 	return $e;
		 }
	}
}