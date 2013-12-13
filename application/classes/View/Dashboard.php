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
	* @var Title Titre pour le dashboard
	*/
	public $title = "Guidoline — Dashboard";

	/**
	* @var Instance de membre
	**/
	public $member;

	public function __construct()
	{
		parent::__construct();
		// Instance de membre pour le formulaire
		$this->member = ORM::factory('member');

		// if 
	}

	/**
	* Retourne les derniers membres ajoutés
	*
	* @return 	array
	**/
	public function last_members_create()
	{
		$members = array();

		foreach( ORM::factory('Member')
			->order_by('created', 'desc')
			->limit(10)
			->find_all() as $member)
		{
			$members[] = array(
				'member' => $member,
			);
		}

		return $members;
	}

	/**
	* Retourne les derniers membres modifiés
	*
	* @return	array
	**/
	public function last_members_update()
	{
		$members = array();

		foreach( ORM::factory('Member')
			->order_by('updated', 'desc')
			->limit(10)
			->find_all() as $member)
		{
			$members[] = array(
				'member'	=> $member,
			);
		}

		return $members;
	}
}