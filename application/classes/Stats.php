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

/*
* Note about stats :
Nombre de membres sur l'année en cours
Nombre de membres dont la première date d'adhésion est 2013 / nombre total de membre
Nombre de membres avec une adhésion sur n,n-1,n-2 / nombre total de membre *chiant* -_-
Nombres de membres de moins de 25 ans / nombre total de membre
*/
class Stats
 // extends Controller_App
{
	/**
	* @vars Objet ORM des membres
	**/
	public $members;

	/**
	* @vars Nombre d'adérents
	**/
	public $count_members;
	// public static function factory($file = NULL, array $data = NULL)
	// {
	// 	return new View($file, $data);
	// }

	/**
	* @vars Nombre d'adhérents actifs
	**/
	public $count_active_members = 0;

	/**
	* @vars Nombre nouvelles adhésion durant l'année en cours
	**/
	public $new_membersship_during_year;


	/**
	* Pour appler toutes les méthode de manière statique et dynamique
	*/
	public static function factory()
	{
		return new Stats();
	}

	public function __construct()
	{
		$this->members = ORM::factory('Member');
		$this->count_members = $this->members->count_all();
		
		foreach ($this->members->find_all() as $member)
		{
			if ($member->last_valid_subscriptions())
			{
				$this->count_active_members ++;
			}
		}

		$this->new_membersship_during_year = $this->members->where(DB::expr("EXTRACT(YEAR FROM member.created)"), '=', date('Y', time()))->count_all();
	}

	/**
	* Pourcentage de membres actifs
	**/
	public function percentage_new_members_during_year()
	{
		return ($this->count_members / $this->new_membersship_during_year ) * 100;
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
		$last_valid_members_during_year = 0;

		foreach (ORM::factory('member')->find_all() as $member) {
			if (
				$member->subscriptions_members->where(DB::expr("EXTRACT(YEAR FROM subscriptions_member.created)"), '=', date('Y', time()))->find()->loaded()
				AND  
				$member->subscriptions_members->where(DB::expr("EXTRACT(YEAR FROM subscriptions_member.created)"), '=', date('Y', time()) - 1)->find()->loaded()
				AND 
				$member->subscriptions_members->where(DB::expr("EXTRACT(YEAR FROM subscriptions_member.created)"), '=', date('Y', time()) - 2)->find()->loaded()
			)
				{
					$last_valid_members_during_year ++;
				}
		}

		return $last_valid_members_during_year / $this->count_members * 100;
	}

	/**
	* Moyenne d'age
	**/
	public function average_age()
	{
		$age = 25;

		// Membres ayant une date anniversaire spécifié
		$ma = ORM::factory('Member')->where('birthdate', '!=', NULL)->count_all();
		// Membres ayant une date anniversaire et un age inférieur à une valeur
		$c = $this->members->where(DB::expr("member.birthdate + INTERVAL {$age} YEAR"), '>', date('Y-m-d H:i:s', time()))->count_all();

		return $c / $ma * 100;
	}

}