<?php defined('SYSPATH') or die ('No direct script access');

/**
* Tous les contrôleur de l'application doivent étendre le contrôleur "App" pour profiter des proprietés et les méthodes de base de l'application.
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_App extends Controller {

	/**
	* @var Layout Propriété pour le layout de base de l'application `View/Layout.php`.
	**/
	protected $layout;

	/**
	* Créer une instance de Kostache pour le layout de base de l'application.
	*
	* @param   Request   $request  Request that created the controller
	* @param   Response  $response The request's response
	* @return  void
	**/
	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->layout = Kostache_Layout::factory();
	}

	/**
	* Afficher le profil membre
	**/
	function action_profil()
	{
		$view = new View_Members_Profil;
		$view->user = ORM::factory('Member', $this->request->param('id'));
		$this->response->body($this->layout->render($view));
	}

}