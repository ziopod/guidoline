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

class View_Master {

	/**
	 * @var String Titre par défaut.
	 */
	public $title = "Guidoline";

	/**
	 * @var String Propriété de language (internationalisation).
	 */
	public $lang;

	/**
	 * @var String Le rendu HTML du profiler de Kohana
	 */
  public $profiler;

  /**
   * @var Boolean Rendu autoamtique de la vue
   */
  public $auto_render = TRUE;

  /**
   * @var Array Notifications de formulaire
   */
  protected $_notifications;

  /**
   * @var Array $_html_form HTML Form Help strorage
   */
  protected $_html_form;

  /**
   * @var Array Erreurs de formulaire
   */
  protected $_html_form_errors;


	/**
	* Initialisation de quelques valeurs pour le layout de base.
	*
	* @return void
	**/
	public function __construct()
	{
		$this->lang = I18n::lang();
		$this->profiler = View::factory('profiler/stats');
	}

  /**
   * Style par défaut
   *
   * ~~~
   * array (
   *  array('style' => array(
   *    'href' => 'path/to/script.js',
   *    'media' => 'screen',
   *  ))
   * )
   * ~~~
   *
   * @return Array
   */
  public function styles()
  {
    return array(
      array('style' => array(
        'href' => '/assets/css/styles.css',
      )),
    );
  }

	/**
   * Scripts par défaut
   *
   * ~~~
   * array (
   *  array('script' => array(
   *    'src' => 'path/to/script.js',
   *    'defer' => 'defer',
   *  ))
   * )
   * ~~~
   *
   * @return Array
   */
  public function scripts()
  {
    return array(
      array('script' => array(
        'src' => '/assets/scripts/general.js',
        'defer' => 'defer',
      )),
      array('script' => array(
        // 'src' => 'https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js',
        'src' => '/assets/scripts/svg-injector.min.js',
      )),
    );
  }

  public function version()
  {
    return VERSION;
  }
  /**
	 * Navigation.
   *
   * ~~~
   *  array('link' => array(
   *    'url' => 'dashboard',
   *    'name'	=> "Tabeau de bord",
   *    'title'	=> "Aller sur le tableau de bord",
   *    'icon' => array(
   *       'name' => 'fa-home',
   *       'modifier' => 'has-text-success'
   *     ),
   *    'class' => 'button is-link',
   *  )),
   * ~~~
	 *
	 * Marquage automatique du lien courant.
   *
   * [Documentation icônes](https://bulma.io/documentation/elements/icon)
	 *
	 * @return 	Array
	 */

	public function menu()
	{
    $current_route = Route::name(Request::current()->route());

    return array(
      'start' => array('items' => array(
        array('item' => array(
          'is_active' => $current_route === 'default',
          'href' => Route::url('default'),
          'label' => 'Tableau de bord',
        )),
        array('item' => array(
          'is_active' => $current_route === 'members',
          'href' => Route::url('members'),
          'label' => 'Adhérents',
        )),
        array('item' => array(
          'is_active' => $current_route === 'forms',
          'href' => Route::url('forms'),
          'label' => 'Bulletins',
        )),
        // array('item' => array(
        //   'is_active' => $current_route === 'dues',
        //   'href' => Route::url('dues'),
        //   'label' => 'Adhésions',
        // )),
        )),
        'footer' => array(
          'text' => array(
            'content' => 'Version ' . $this->version(),
          ),
          'links' => array(
            array('link' => array(
              'href' => Route::url('page.about'),
              'name' => "À propos de Guidoline",
            )),
          )
        )
    );
		$items = array(
      array('separator' => array(
        'label' => "Séparateur",
        'icon' => 'example',
      )),
      array('link_list' => array('links' => array(
        array('link' => array('name' => 'Lien A')),
        array('link' => array(
          'name' => 'Lien B',
          'link_list' => array('links' => array(
              array('link' => array('name' => 'Lien B.1')),
              array('link' => array('name' => 'Lien B.2')),
          )),
        )),
      ))),
      array('link' => array(
        'href' => 'dashboard',
        'name'	=> 'Tabeau de bord',
        'title'	=> 'Aller sur le tableau de bord',
        'has-icon' => TRUE,
        'icon' => array(
          'modifier' => 'has-text-success',
          // 'name' => 'fa-home',
          'stack' => array(
            'fa-circle fa-stack-2x',
            'fa-flag fa-stack-1x fa-inverse',
          ),
        ),
      )),
      array('link' => array(
        'name' => 'Adhérents'
      )),
      array('link' => array(
        'name' => 'Adhésions'
      )),
      array('link' => array(
        'name' => 'Bulletins d\'adhésions'
      )),
      array('link' => array(
        'name' => 'Utilisateurs'
      )),
      array('link' => array(
        'name' => 'Compte'
      )),
      array('link' => array(
        'name' => 'Déconnexion'
      )),
    );

    return array(
      'has_items' => ! empty($items),
      'items' => $items,
    );
	}


  /**
   * Notifications courantes
   *
   * @return Array
   */
  public function notifications()
  {
    return $this->_notifications;
  }

	/**
	* @vars Navigation_links Navigation par défaut pour toute l'application.
	*
	* __À noter__ : Le système de navigation ne gère pas les "sous menu" pour le moment.
	**/
	public $navigation_links = array(
		'dashboard'	=> array(
			'url' => 'dashboard',
			'name'	=> "Tabeau de bord",
			'title'	=> "Aller sur le tableau de bord",
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
		// 'userguide' => array(
		// 	'url'	=> 'guide-api',
		// 	'name'	=> "API guide",
		// 	'title'	=> "Need help?",
		// 	),
		// 'sandbox'	=> array(
		// 	'url'	=> 'sandbox',
		// 	'name'	=> "Sandbox",
		// 	'title'	=> "let's plays",
		// 	)
		);

  /**
   * Paramètre globaux
   *
   * @param   String    $key    Identifiant de paramètre
   * @return  Mixed
   */

  public function guidoline($key = NULL)
  {
    $config = Kohana::$config->load('guidoline');

    if ($key)
    {
      return Arr::get($config, $key);
    }

    return $config;
  }

  /**
   * Lambdas Mustache pour les filtres
   *
   * https://github.com/bobthecow/mustache.php/wiki/Mustache-Tags#lambdas
   *
   * @return String
   */
  public function is_filter_active()
  {
    return function($filter, $helper)
    {
      if ($filter === Request::current()->param('filter'))
      {
        return "is-active";
      }

      return FALSE;
    };
  }

  /**
   * Lambdas Mustache pour Markdownifier du texte
   *
   * @return String
   */
  public function markdownify()
  {
    // return function($text) { return "<i>$text</i>"; };
    return function($text, Mustache_LambdaHelper $helper)
    {
      $md = new Parsedown();
      $md->setBreaksEnabled(TRUE);
      return $md->text($helper->render($text));
    };
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
