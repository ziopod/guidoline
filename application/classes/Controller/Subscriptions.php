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
	* Afficher toutes es adhésions
	*
	* @return @void
	**/
	public function action_index()
	{
		$view = new View_Subscriptions_Index;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Afficher le détail d'une adhésion 
	*
	* @return @void
	**/
	public function action_detail()
	{
		$view = new View_Subscriptions_Detail;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Ajouter ou modifier une adhésion
	**/
	public function action_edit()
	{
//		$id = $this->request->param('id');
//		$subscription = ORM::factory('subscription', $id);
		//$this->_show_edit_form();//$subscription);
		$view  = new View_Subscriptions_Edit;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Sauvegarder une adhésion
	**/
	public function action_save()
	{
		$view  = new View_Subscriptions_Edit;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Utilitaire pour afficherle formulaire d'édition
	**/
	private function _show_edit_form($subscription = NULL, $errors = NULL)
	{
		$view  = new View_Subscriptions_Edit;
		$this->response->body($this->layout->render($view));
	}
}