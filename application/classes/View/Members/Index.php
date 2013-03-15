<?php

/**
* Modèle de vue pour l'affichage de l'index des membres `templates/members/index.mustache`
* 
* @package    Guidoline
* @category   View model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Members_Index extends View_Layout {

	/**
	* @vars 	Title 	Le titre de la page
	**/
	public $title = "Membres | Guidoline";

	/**
	* Retourne la liste de tous les memebres
	**/
	public function members()
	{
		return ORM::factory('Member')->find_all()->as_array();
	}
}