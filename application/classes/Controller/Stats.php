<?php defined('SYSPATH') or die ('No direct script access');


/**
* Interface pour récupérer diverses statistiques
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/


class Stats // extends Controller_App
{
	/**
	* @vars Nombre d'adérents
	**/
	public $members_count;

	/**
	* @vars Nombre d'adhérents actifs
	**/
	public $members_actives_count;

	public function __construct()
	{
		$members = ORM::factory('members');
		$this->members_count = $members->count_all();
		
		foreach ($members->find_all() as $member)
		{
			if ($member->last_valid_subscription())
			{
				$this->members_active_count ++;
			}
		}
	}

	/**
	* Taux de nouveaux membres au cours de cette année
	**/
	public function action_new_members_during_year()
	{

	}

	/**
	* Taux de renouvellement des adhérents
	**/ 
	public function action_turnover()
	{
		$this->response->body(Json_encode());
	}

	/**
	* Moyenne d'age
	**/
	public function action_average_age()
	{

	}

}