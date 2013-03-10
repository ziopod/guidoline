<?php defined('SYSPATH') or die ('No direct script access');

/**
* Tous les contrôleur de l'application doivent étendre le contrôleur "App" pour profiter des méthodes de base de l'application.
* 
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/


class Controller_App extends Controller {
	
	protected $layout;

	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->layout = Kostache_Layout::factory();
	}

}