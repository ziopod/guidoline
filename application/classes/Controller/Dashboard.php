<?php defined('SYSPATH') or die ('No direct script access');
/**
* Le contrôleur "Dashboard" est utilisé pour gérer les actions Lié à la page d'accueil de l'application.
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_Dashboard extends Controller_App {

	public function action_index()
	{
		$this->response->body($this->layout->render(new View_Dashboard));
	}
}