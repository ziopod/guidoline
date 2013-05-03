<?php

/**
* Modèle de vue pour l'affichage de l'index des adhésions du membre `templates/members/subscriptions/index.mustache`
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Members_Subscriptions_Index extends View_Layout {

	/**
	* @vars 	Title 	Le titre de la page
	**/
	public $title = "Inscriptions | Guidoline";

	public $member;

	public function member()
	{
		$this->member =  ORM::factory('Member', Request::initial()->param('id'));
		return $this->member;
	}

	public function subscriptions_member()
	{
		$subscriptions_members =  $this->member->subscriptions_members;
		return ($this->member->subscriptions_members->count_all()) ? $subscriptions_members->find_all() : NULL;
	}

}