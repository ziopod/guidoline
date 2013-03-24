<?php

/**
* Modèle de vue pour l'affichage de l'index des adhésion `templates/subscirptions/index.mustache`
*
* @package		Guidoline
* @category		View Model
* @author		Ziopod | ziopod@gmail.com
* @copyright	BY-SA 2013 Ziopod
* @license		http://creativecommons.org/licenses/by-sa/3.0/
*/

class View_Subscriptions_Index extends View_Layout {

	/**
	* @vars		Title	Le titre de la page
	**/
	public $title = "Adhésions - Guidoline";

	/**
	* Retourne la liste de toutes les adhésions
	**/
	public function subscriptions()
	{
		$subscriptions = ORM::factory('Subscription');
		return $subscriptions; //->find_all();
	}
}
?>