<?php
/**
* View Layout
*
* Default values and method for Views
**/

class View_Layout {
	
	/**
	* Custom values for layout
	*/
	public $title = "Guidoline";
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
	* System values for layout
	*/
	public $lang;
	public $partials = array(
//		'users'	=> 'partials/users', /* Exemple */
		); /* N'est pas utilisé pour le moment */

	/**
	* Init some stuff
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
	* Navigation
	**/
	public function navigation() // get_nav_links
	{
		$current = 'home';
		$nav = $this->navigation_links;
		$nav[$current]['current'] = TRUE;
		return $this->_array_in_object($nav, 'link');
	}

	/**
	* Base URL
	**/
	public function base_url()
	{
		return URL::base(FALSE, TRUE);
	}

	/**
	* Utilities
	**/

	protected function _array_in_object($array, $term = 'item')
	{
		$result = array();

		if (!empty($array))
		{
			foreach ($array as $key => $link)
			{
				$ob = (object) NULL;
				$ob->$term = $link;
				$result[] = $ob;
			}
		}

		return $result;
	}

}