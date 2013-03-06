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
			'title'	=> 'Go to users list')
		);

	/**
	* System values for layout
	*/
	public $lang;
	public $partials = array(
//		'users'	=> 'partials/users',
		);

	/**
	* Init some stuff
	**/
	public function __construct()
	{
		$this->lang = I18n::lang();
	}

	/**
	* Navigation
	**/
	public function navigation() // get_nav_links
	{
		$current = 'home';
		echo '<code><strong>Request::uri()</strong></code>';
		echo Debug::vars(Request::initial()->uri());
		echo '<code><strong>Request::controller()</strong></code>';
		echo Debug::vars(Request::initial()->controller());
		echo '<code><strong>Request::action()</strong></code>';
		echo Debug::vars(Request::initial()->action());
		echo '<code><strong>Request::route()</strong></code>';
		echo Debug::vars(Request::initial()->route());
		$nav = $this->navigation_links;
		$nav[$current]['current'] = TRUE;
		return $this->_array_in_object($nav, 'link');
	}

	/**
	* Base URL
	**/
	public function base_url()
	{
		return url::base(FALSE, TRUE);
	}

	/**
	* Utilities
	**/

	function _array_in_object($array, $term = 'item')
	{
		$result = array();

		foreach ($array as $key => $link)
		{
			$ob = (object) NULL;
			$ob->$term = $link;
			$result[] = $ob;
		}

		return $result;
	}
}