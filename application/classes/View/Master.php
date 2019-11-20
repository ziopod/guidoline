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
	* Initialisation de quelques valeurs pour le layout de base.
	*
	* @return void
	**/
	public function __construct()
	{
		// Ajout de la balise "script" pour les liens vers les scripts
		// foreach ($this->scripts as $key => $ob)
		// {
		// 	$this->scripts[$key]['script'] = HTML::script($ob['script']);
		// }

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
	 * @return 	Object 	La navigation sous forme d'objet
	 */
  public function example()
  {
    return array('url' => '/adherents/active');
  }
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
          'is_active' => $current_route === 'subscriptions',
          'href' => Route::url('subscriptions'),
          'label' => 'Bulletins',
        )),
        // array('item' => array(
        //   'is_active' => $current_route === 'dues',
        //   'href' => Route::url('dues'),
        //   'label' => 'Adhésions',
        // )),
      ))
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
	* L'URL racine.
	*
	* @return 	String  La portion de base de l'url
	**/
	public function base_url()
	{
		return URL::base(FALSE, TRUE);
	}

  /**
   * Formulaire d'édition d'adhérent
   *
   * @todo    Pourrais être automatisé en utilisant `model::labels()`,
   *          `model::names() et `model::keys()` pour peupler les clefs.
   * @todo    Ajouter de l'automatisation et placer la partie `data`
   *          dans le modèle `Model_Member::form()`.
   * @param   ORM     $member   Modèle ORM Member
   * @return  Array
   */
  protected $_form_member;

  public function form_member(
    $member,
    $notifications = array(),
    $errors = array())
  {
    if ( ! $this->_form_member)
    {
      $this->_form_member = array(
        'action' => '/adherents/edit/' . $member['id'] . '#form_member',
        'member_id' => $member['id'],
        // 'notifications' => array(
        //     array('notification' => array(
        //       'kind' => 'warning', // `info | sucess | warning | danger`
        //       'content' => "Exemple de message",
        //       'deletable' => TRUE,
        //     )),
        //     array('notification' => array(
        //       'kind' => 'danger', // `info | success | warning | danger`
        //       'content' => "Attention !",
        //     )),
        // ),
        'data' => array(
          // Identité
          'name' => array(
            'field' => array(
              'label' => 'Nom',
              'name'  => 'lastname',
              'id'    => 'lastname',
              'value' => $this->member()['lastname'],
              'error' => Arr::get($errors, 'lastname'),
              )
            ),
            'firstname' => array(
              'field' => array(
                'label' => 'Prénom',
                'name'  => 'firstname',
                'id'    => 'firstname',
                'value' => $this->member()['firstname'],
                'error' => Arr::get($errors, 'firstname'),
            )
          ),
          'birthdate' => array(
            'field' => array(
              'label' => 'Date de naissance',
              'name'  => 'birthdate',
              'id'    => 'birthdate',
              'value' => $this->member()['birthdate'],
            )
          ),
          'select_genders' => array(
            'field' => array(
              'label'   => 'Genre',
              'name'    => 'gender',
              'id'      => 'gender',
              'data' => $this->member()['select_genders'],
            ),
          ),
          // Contact
          'email' => array(
            'field' => array(
              'label' => 'Addresse email',
              'name'  =>  'email',
              'id'    => 'email',
              'value' => $this->member()['email'],
              'error' => Arr::get($errors, 'email'),
            ),
          ),
          'phone' => array(
            'field' => array(
              'label' => 'Téléphone',
              'name'  =>  'phone',
              'id'    => 'phone',
              'value' => $this->member()['phone'],
            ),
          ),
          // Addresse
          'address' => array(
            'street' => array('field' => array(
              'label' => 'Rue',
              'name'  => 'address[street]',
              'id'    => 'address-street',
              'value' => $this->member()['street'], // $this->member()['address']['street']
            )),
            'zipcode' => array('field' => array(
              'label' => 'Code postal',
              'name'  => 'address[zipcode]',
              'id'    => 'address-zipcode',
              'value' => $this->member()['zipcode'],
            )),
            'city' => array('field' => array(
              'label' => 'Ville',
              'name'  => 'address[city]',
              'id'    => 'address-city',
              'value' => $this->member()['city'],
            )),
            'country' => array('field' => array(
              'label' => 'Pays',
              'name'  => 'address[country]',
              'id'    => 'address-country',
              'value' => $this->member()['country'],
            ))
          ),
          'exemple' => array(
            'field' => array(
              'type' => 'text', // Juste informatif
              'label' => 'Exemple',
              'name' => 'exemple',
              'id' => 'exemple-id',
              'value' => 'Des donnnées', // À générer
              'placeholder' => 'Write here',
              'required' => 'required',
              'size' => 3,
              'error' => 'Ceci est une erreur', // À génerer
            )
          ),
          'exemple_select' => array(
            'field' => array(
              'label' => "Exemple with select",
              'name' => 'exemple-with-select',
              'id' => 'exemple-with-select-id',
              // 'multiple' => 'multiple', // Require `data.size`
              'data' => array('options' => array(
                'size' => 4,
                array('option' => array(
                  'name' => 'option-a',
                  'value' => 'Value for option A',
                )),
                array('option' => array(
                  'name' => 'option-b',
                  'value' => 'Value for option B',
                  'selected' => 'selected',
                )),
                array('option' => array(
                  'name' => 'option-c',
                  'value' => 'Value for option C',
                )),
                array('option' => array(
                  'name' => 'option-d',
                  'value' => 'Value for option d',
                  'selected' => 'selected',
                )),
              )),
            )
          ),
        ),
      );
      $this->_form_member['notifications'] = $notifications;
    }

    return $this->_form_member;
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
