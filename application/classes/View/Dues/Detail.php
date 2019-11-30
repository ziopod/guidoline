<?php

/**
* Affichage d'une adhésion
*
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Dues_Detail extends View_Master {


	/**
	* @var 	String  $title 	Le titre de la page
	**/
	public $title = "Adhésion - Guidoline";

  /**
   * @var Array $due   Due data storage
   */
  public $due;

  /**
   * Due storage
   */
  public function __construct()
  {
    parent::__construct();
    $due_id = Request::initial()->param('due_id');
    $due = ORM::factory('Due', $due_id);

    if ( ! $due->loaded())
    {
      throw new HTTP_Exception_404("La cotisation $due_id est introuvable.");
    }

    $this->due = $due->as_array('pretty_created,form,member');

  }

}
