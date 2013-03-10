<?php

/**
* Le modèle de vue `View/Sandbox/Index.php` fournis les propriétés et méthodes pour le template 'templates/sandbox/index.mustache'
*
* @package    Guidoline
* @category   View model
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Sandbox_Index extends View_Layout {

	/**
	* @vars Title Le titre de la page.
	**/
	public $title = "Sandbox";

	/**
	* @vars Simple_data Un exmeple de propriété de base.
	**/
	public $simple_data = "Hello cat";

	/**
	* @vars Simple_object Un exemple d'objet de base.
	**/
	public $simple_object = array(
		'cat'	=> "Miaou!",
		'dog'	=> "Wouaf!",
		);

	/**
	* @vars simple_result Un exemple de tableau de résultat.
	**/
	public $simple_result = array(
		array(
			'name'	=> 'Nyan cat',
			'url'	=> 'http://www.prguitarman.com/comics/poptart1red1.gif',
			),
		array(
			'name'	=> 'Kitty',
			'url'	=> 'http://24.media.tumblr.com/tumblr_m4cs1a9SmW1rp69vuo1_500.gif'
			),
		array(
			'name'	=> 'Poppy',
			'url'	=>'http://24.media.tumblr.com/tumblr_meo3edxSbo1rvl3nyo1_500.gif'
		),
	);

	/**
	* Récupérer l'objet de base
	*
	* @return 	Array 	Pas grand chose pour le moment
	**/
	public function get_simple_object()
	{

	}

	/**
	* Récupérer un résultat de DB de base
	*
	* @return 	Array 	Pas grand chose pour le moment
	**/
	public function get_simple_result()
	{
		return DB::select()->from('unicorns')->execute()->as_array();
	}

}