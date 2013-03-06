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

	/**
	* System values for layout
	*/
	public $lang;

	/**
	* Init some stuff
	**/
	public function __construct()
	{
		$this->lang = I18n::lang();
	}

	/**
	* Base URL
	**/
	public function base_url()
	{
		return url::base(FALSE, TRUE);
	}
}