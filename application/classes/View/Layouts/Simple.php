<?php

/**
* Le modèle de vue `View/Layouts/Simple.php` fournis les propriétés et méthodes pour le template `templates/layouts/simple.mustache`.
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Layouts_Simple {
	
	/**
	* @vars Title Titre par défaut pour toutes les vues de l'application.
	*/
	public $title = "Guidoline";

	/**
	* @vars Scripts	Scripts par défaut pour le header HTML
	**/
	public $scripts = array(
		array('script' => 'http://code.jquery.com/jquery-1.9.1.min.js'),
	);
	
	/**
	* @vars Lang Propriété de language (internationalisation).
	**/
	public $lang;

	/**
	* Initialisation de quelques valeurs pour le layout de base.
	*
	* @return void
	**/
	public function __construct()
	{
		// Ajout de la balise "script" pour les liens vers les scripts
		foreach ($this->scripts as $key => $ob)
		{
			$this->scripts[$key]['script'] = HTML::script($ob['script']);
		}

		$this->lang = I18n::lang();
	}

	/**
	* L'URL racine.
	*
	* @return 	String  La portion de base de l'url 
	**/
	public function base_url()
	{
		return URL::base(FALSE, TRUE);
	}

}