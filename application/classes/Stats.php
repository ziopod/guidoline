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


class Stats
 // extends Controller_App
{
	/**
	* @vars Nombre d'adérents
	**/
	public $count_members;
	// public static function factory($file = NULL, array $data = NULL)
	// {
	// 	return new View($file, $data);
	// }

	public static function factory()
	{
		return new Stats();
	}

	/**
	* @vars Nombre d'adhérents actifs
	**/
	public $count_active_members = 0;

	/**
	* @vars Nombre nouvelles adhésion durant l'année en cours
	**/
	public $new_membersship_during_year;


	public function __construct()
	{
		$members = ORM::factory('member');
		$this->count_members = $members->count_all();
		
		foreach ($members->find_all() as $member)
		{
			if ($member->last_valid_subscriptions())
			{
				$this->count_active_members ++;
			}
		}

		$this->new_membersship_during_year = $members->where(DB::expr("EXTRACT(YEAR FROM member.created)"), '=', date('Y', time()))->count_all();
	}

	/**
	* Pourcentage de membres actifs
	**/
	public function percentage_new_members_during_year()
	{
		return ($this->count_active_members / $this->new_membersship_during_year ) * 100;
	}

	/**
	* Taux de nouveaux membres au cours de cette année
	**/
	public function new_membersship_during_year()
	{
		return $this->new_membersship_during_year;
	}

	/**
	* Taux de renouvellement des adhérents
	**/ 
	public function turnover()
	{
		$this->response->body(Json_encode());
	}

	/**
	* Moyenne d'age
	**/
	public function average_age()
	{

	}

}