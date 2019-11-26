<?php

/**
* Le formulaire d'ajout ou de modification d'une adhésion
*
* @package		Guidoline
* @category		View Model
* @author 		Ziopod | ziopod@gmail.com
* @copyright	BY-SA 2013 Ziopod
* @license		http://creativecommons.org/licenses/by-sa/3.0/
**/

class View_Forms_Edit extends View_Master {

	/**
	* @vars		string	Le titre de la page
	**/
	public $title = "Édition d'un bulletin d'adhésion | Guidoline";

  /**
   * @var ORM   $_form  Current ORM Model_Form
   */
  protected $_form;


  /**
   * Édition d'un bulletin
   *
   * Contrôler si le bulletin est modifiable / supprimable.
   *
   * Traiter et sauvegarder les données POST
   */
  public function __construct()
  {
    parent::__construct();

    // Save post data
    if (Request::current()->method() === HTTP_Request::POST)
    {
      $form = ORM::factory('Form', Request::current()->param('form_id'));
      $form->values(Request::current()->post(), array(
        'title',
        'heading',
        'description',
        'price',
        'currency',
        'currency_code',
        'date_start',
        'duration',
        'start_at_due',
        'free_price',
        'is_active',
      ));

      try
      {
        $form->save();
      }
      catch (ORM_Validation_Exception $e)
      {
        $this->_errors = $e->errors();
      }
    }
  }

  public function form()
  {
    if ( ! $this->_form)
    {
      $this->_form = $this->_form();
    }

    return $this->_form;
  }

  /**
   * Query for Model_Form
   *
   * @return ORM
   */
  protected function _form()
  {
    return ORM::factory(
      'Form',
      Request::current()->param('form_id')
    );
  }
  /**
   * HTML Helper for populating form
   *
   * @return Array
   */

  public function html_form()
  {
    if ( ! $this->_html_form)
    {
      $this->_html_form = $this->_form()->html_form();
    }

    return $this->_html_form;
  }
}
