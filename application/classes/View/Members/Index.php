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
	* @vars	Members_count	Le nombre de membres total
	**/
	public $members_count;

	/**
	* Retourne la liste de tous les memebres
	**/
	public function members()
	{
		$members = ORM::factory('Member');
		$this->members_count = $members->count_all();
		return $members->find_all()->as_array();
	}
}