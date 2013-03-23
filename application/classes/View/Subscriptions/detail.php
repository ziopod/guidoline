<?php

/**
* Modèle de vue pour l'affichage du détail d'une adhésion `templates/subscriptions/subscriptions/detail.mustache`
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Subscriptions_Detail extends View_Layout {

	/**
	* @vars 	Title 	Le titre de la page
	**/
	public $title = "Adhésion - Guidoline";

	public function subscription()
	{
		$subscription =  ORM::factory('Subscription', Request::initial()->param('id'));
		return $subscription;
	}

}