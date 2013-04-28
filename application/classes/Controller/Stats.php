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


class Controller_Stats extends Controller_App
{

	/**
	* Retourn le taux de renouvellmemtn des adhérents
	**/ 
	public function action_turnover()
	{

		$this->response->body(Json_encode());
	}
}