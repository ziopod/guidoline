<?php

/**
* Le modèle de vue `View/Layout.php` fournis les propriétés et méthodes pour le template `templates/layout.mustache`.
* 
* @package    Guidoline
* @category   View model
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Layout {
	
	/**
	* Titre par défaut pour toutes les vues de l'application.
	*/
	public $title = "Guidoline";
	/**
	* Navigation par défaut pour toute l'application.
	*
	* __À noter__ : Le système de navigation ne gère pas les "sous menu" pour le moment.
	**/
	public $navigation_links = array(
		'home'	=> array(
			'url' => '',
			'name'	=> 'Home',
			'title'	=> 'Go to home',
			),
		'user'	=> array(
			'url'	=> 'users',
			'name'	=> 'Users',
			'title'	=> 'Go to users list',
			),
		'userguide' => array(
			'url'	=> 'guide-api',
			'name'	=> 'API guide',
			'title'	=> 'Need help?',
			)
		);

	/**
	* Propriété de language (internationalisation)
	**/
	public $lang;

	/**
	* Permet de modifié l'emplacement des "partials" pour le moteur Mustache (pas utilisé pour le moment).
	*
	* Exemple : 
	*		array(
	*			'navigation'	=> 'navigation/users',
	*		)
	**/
	public $partials = array();

	/**
	* Initialisation de quelques valeurs pour le layout de base.
	**/
	public function __construct()
	{
		$this->lang = I18n::lang();
		// echo '<code><strong>Request::uri()</strong></code>';
		// echo Debug::vars(Request::initial()->uri());
		// echo '<code><strong>Request::controller()</strong></code>';
		// echo Debug::vars(Request::initial()->controller());
		// echo '<code><strong>Request::action()</strong></code>';
		// echo Debug::vars(Request::initial()->action());
		// echo '<code><strong>Request::route()</strong></code>';
		// echo Debug::vars(Request::initial()->route());
	}

	/**
	* Retourne les valeurs pour la navigation.
	*
	* `current` determine le lien actif (pas implémenté pour le moment).
	**/
	public function navigation()
	{
		$current = 'home';
		$nav = $this->navigation_links;
		$nav[$current]['current'] = TRUE;
		return $this->_array_in_object($nav, 'link');
	}

	/**
	* Retourne la base URL.
	**/
	public function base_url()
	{
		return URL::base(FALSE, TRUE);
	}

	/**
	* Méthode utilitaire pour Mustache, encapsule un tableau dans objet.
	**/
	protected function _array_in_object($array, $term = 'item')
	{
		$result = array();

		if (!empty($array))
		{
			foreach ($array as $key => $value)
			{
				$ob = (object) NULL;
				$ob->$term = $value;
				$result[] = $ob;
			}
		}

		return $result;
	}

}