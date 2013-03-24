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
	*
	* Nous testerons ici de déporter les procédures vers le modèle de vue.
	* Interface API :
	*  - pas d'action "save"
	*  - obligation de doubler les procédures d'ajout et d'enregistrement (non respect DRY)
	*  - les procédures de validation et d'enregistrement des méthodes "Members::action_edit()" et "Members::action_save()" sont plus viable en interfacage API
	**/
	public function action_edit()
	{
		// Préciser ici, en variable de session ou cookie, si l'enregistrement ou l'ajout résussi doit être redirigé.
		$view  = new View_Subscriptions_Edit;
		$this->response->body($this->layout->render($view));
	}
}