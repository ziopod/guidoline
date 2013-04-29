<?php

/**
* Modèle de vue pour l'affichage de l'index des membres `templates/members/index.mustache`
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Members_Index extends View_Layout {

	/**
	* @vars 	Title 	Le titre de la page
	**/
	public $title = "Membres - Guidoline";

	/**
	* Instance de la classe Stats
	**/
	protected $stats;

	public function __construct()
	{
		parent::__construct();
		$this->stats = new Stats();
	}

	/**
	* Retourne la liste de tous les membres
	**/
	public function members()
	{
		$members = ORM::factory('Member');
		return $members->find_all();
	}

	/**
	* Nombres d'adhérents total
	**/
	public function count_members()
	{
		return $this->stats->count_members;
	}

	/**
	* Nombre d'ahérents actifs
	**/
	public function count_active_members()
	{
		return $this->stats->count_active_members;
	}

	/**
	* Nombre de nouveau membres durant cette année
	**/
	public function new_membersship_during_year()
	{
		return $this->stats->new_membersship_during_year();
	}

	/**
	* Pourcentage de membres actifs
	**/
	public function percentage_new_members_during_year()
	{
		return $this->stats->percentage_new_members_during_year();
	}

	/**
	* Membre de moins de 25 ans
	**/
	public function average_age()
	{
		return $this->stats->average_age();
	}
}