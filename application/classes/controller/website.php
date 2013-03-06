<?php defined('SYSPATH') or die ('No direct script access');

/**
* Méthodo : Migrer ce fichier vers une extension directe de la classe Controller?
**/

class Controller_Website extends Controller {
	
	protected $layout;

	public function __construct(Request $request, Response $response)
	{
		parent::__construct($request, $response);
		$this->layout = Kostache_Layout::factory();
	}

}