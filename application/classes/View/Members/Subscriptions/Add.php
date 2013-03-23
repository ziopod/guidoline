<?php

/**
* Modèle de vue pour l'édition d'une adhésion d'un membre `templates/members/subscriptions/edit.mustache`
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Members_Subscriptions_Add extends View_Layout {

	public function subscriptions()
	{
		return ORM::factory('subscription')->find_all();
	}

}