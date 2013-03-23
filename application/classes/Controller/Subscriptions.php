<?php defined('SYSPATH') or die ('No direct script access');

/**
* Subscriptions, sert les pages d'adhésions
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_Subscriptions extends Controller_App {

	/**
	*	Affiche le détail d'une adhésion 
	*
	* @return @void
	**/

	public function action_detail()
	{
		$view = new View_Subscriptions_Detail;
		$this->response->body($this->layout->render($view));
	}
}