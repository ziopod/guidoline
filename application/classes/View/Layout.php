<?php

/**
* Le modèle de vue `View/Layout.php` fournis les propriétés et méthodes pour le template `templates/layout.mustache`.
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Layout {
	
	/**
	* @vars Title Titre par défaut pour toutes les vues de l'application.
	*/
	public $title = "Guidoline";

	/**
	* @vars Navigation_links Navigation par défaut pour toute l'application.
	*
	* __À noter__ : Le système de navigation ne gère pas les "sous menu" pour le moment.
	**/
	public $navigation_links = array(
		'home'	=> array(
			'url' => '',
			'name'	=> "Home",
			'title'	=> "Go to home",
			),
		'users'	=> array(
			'url'	=> 'users',
			'name'	=> "Users",
			'title'	=> "Go to users list",
			),
		'members'	=> array(
			'url'	=> 'members',
			'name'	=> "Membres",
			'title'	=> 'Afficher les Membres de l\'association',
			),
		'subscriptions'	=> array(
			'url'	=> 'subscriptions',
			'name'	=> 'Adhésions',
			'title'	=> 'Afficher les adhésions',
		),
		'userguide' => array(
			'url'	=> 'guide-api',
			'name'	=> "API guide",
			'title'	=> "Need help?",
			),
		'sandbox'	=> array(
			'url'	=> 'sandbox',
			'name'	=> "Sandbox",
			'title'	=> "let's plays",
			)
		);

	/**
	* @vars Scripts	Scripts par défaut pour le header HTML
	**/
	public $scripts = array(
		array('script' => 'http://code.jquery.com/jquery-1.9.1.min.js'),
		array('script' => 'assets/script/jquery.dataTables.min.js'),
		array('script' => 'assets/script/jquery.jeditable.js'),
		array('script' => 'assets/script/dataTables.bootstrap.js'),
		array('script' => 'assets/script/jquery.nyroModal.custom.js'),
		array('script' => 'assets/script/general.js'),
	);
	
	/**
	* @vars Lang Propriété de language (internationalisation).
	**/
	public $lang;

	/**
	* @vars Partial Permet de modifié l'emplacement des "partials" pour le moteur Mustache (pas utilisé pour le moment).
	*
	* Exemple : 
	*		array(
	*			'navigation'	=> 'navigation/users',
	*		)
	**/
	public $partials = array(
		'sandbox'	=> 'partials/sandbox',
	);

	/**
	* @vars Le profiler de Kohana
	**/
	public $profiler;

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
		$this->profiler = View::factory('profiler/stats');
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
	*
	* @return 	Object 	La navigation sous forme d'objet
	**/
	public function navigation()
	{
		$current = 'home';
		$nav = $this->navigation_links;
		$nav[$current]['current'] = TRUE;
		return $this->_array_in_object($nav, 'link');
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

	/**
	* Méthode utilitaire pour Mustache, encapsule un tableau dans objet.
	*
	* @param 	Array 	Tableau de liens
	* @param 	String 	Le terme à associer à chaque entrée du tableau
	* @return   Object 	Un objet ou chaque terme "$term" représente une entrée du tableau
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