<?php
/**
* Modèle de vue pour l'affichage du détail d'une adhésion `templates/subscriptions/detail.mustache`
*
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Forms_Detail extends View_Master {

	/**
	* @vars 	Title 	Le titre de la page
	**/
	public $title = "Adhésion - Guidoline";

	public function Form()
	{
    $form =  ORM::factory('Form', Request::initial()->param('form_id'))->as_array('dues,dues_all');
		return $form;
	}

}
