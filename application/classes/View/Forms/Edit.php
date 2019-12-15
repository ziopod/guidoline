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
      // $form = ORM::factory('Form', Request::current()->param('form_id'));
      $this->form()->values(Request::current()->post(), array(
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
        $this->form()->save();
      }
      catch (ORM_Validation_Exception $e)
      {
        $this->_html_form_errors = $e->errors('models');
        $labels = array_intersect_key(
          $this->form()->labels(),
          array_flip(array_keys($this->_html_form_errors))
        );
        $this->_notifications[] = array('notification' => array(
          'kind' => 'danger',
          'content' => "La bulletin d'adhésion contient des erreurs, veuillez contrôler le(s) champs suivant(s) : <b>" . implode(', ', $labels) . "</b>.",
        ));
      }
    }
  }

  /**
   * Model_Form
   *
   * @return ORM
   */
  public function form()
  {
    if ( ! $this->_form)
    {
      $this->_form = ORM::factory(
        'Form',
        Request::current()->param('form_id')
      );
    }

    return $this->_form;
  }

  /**
   * HTML Helper for populate form
   *
   * @return Array
   */

  public function html_form()
  {
    if ( ! $this->_html_form)
    {
      $this->_html_form = $this->form()->html_form($this->_html_form_errors);
      $this->_html_form['notifications'] = $this->notifications();
    }

    return $this->_html_form;
  }
}
