<?php

/**
* Modèle de vue pour l'affichage de l'index des adhésion `templates/subscirptions/index.mustache`
*
* @package		Guidoline
* @category		View Model
* @author		Ziopod | ziopod@gmail.com
* @copyright	BY-SA 2013 Ziopod
* @license		http://creativecommons.org/licenses/by-sa/3.0/
*/

class View_Forms_Index extends View_Master {

	/**
	* @vars		Title	Le titre de la page
	**/
	public $title = "Adhésions - Guidoline";

  /**
   * @var Array
   */
  protected $_forms;

	/**
	* Retourne la liste de toutes les adhésions
	**/
	public function forms()
	{
    if ( ! $this->_forms)
    {
      $this->_forms = array(
        'records' => array(),
        'records_count' => 0,
      );

      foreach( ORM::factory('Form')->find_all() as $form)
      {
        $this->_forms['records'][]['form'] = $form->as_array('dues,dues_all');
      }

      $this->_forms['records_count'] = count($this->_forms['records']);
        // echo Debug::vars($this->_forms);
    }

		return $this->_forms;
	}
}
?>
