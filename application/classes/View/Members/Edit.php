<?php

/**
* Le formulaire d'ajout ou de modification d'un adhérent
*
* @package		Guidoline
* @category		View Model
* @author 		Ziopod | ziopod@gmail.com
* @copyright	BY-SA 2013 Ziopod
* @license		http://creativecommons.org/licenses/by-sa/3.0/
**/

class View_Members_Edit extends View_Master {

  /**
	 * @var String Titre pour le tableau de bord
	 */
  public $title = "Guidoline — Edition d'adhérent";

  /**
   * @var Array Notifications de formulaire
   */
  protected $_notifications;

  /**
   * @var Array Errerus de formulaire
   */
  protected $_errors;

  /**
   * Contrôler l'édition possible de l'adhérent
   * Recevoir les données post et sauvegarder les données
   */
  public function __construct()
  {
    $is_new = ! $this->_member()->loaded();

    // Adhérent éligible à l'édition
    if ($is_new && Request::current()->param('id') )
    {
      throw new HTTP_Exception_404("L'adhérent (ID: :id) demandé, n'as pas été trouvé.", array(':id' => Request::current()->param('id')));
    }

    // Enregistrement des données
    if (Request::current()->method() === HTTP_Request::POST)
    {
      $this->_member()
      ->values(
        Request::current()->post(),
        array(
          'name',
          'firstname',
          'email',
          'phone',
          'street',
          'zipcode',
          'city',
          'country',
          'birthdate',
        )
      );
      try
      {
        $this->_member()
        ->save();

        // Enregistrement d'une adhésion

        // Enregistrement des métas

        $this->_notifications[] = array('notification' => array(
          'kind' => 'success',
          'content' => ($is_new ? "Ajout" : "Modifications") . " de la fiche de <b>{$this->_member()->fullname()}</b> effectué.",
        ));
      }
      catch (ORM_Validation_Exception $e)
      {
        // $this->_member = $this->_member();
        $this->_errors = $e->errors('models');
        $labels = array_intersect_key(
            $this->_member()->labels(),
            array_flip(array_keys($this->_errors))
          );
        $this->_notifications[] = array('notification' => array(
          'kind' => 'danger',
          'content' => "La fiche adhérent contient des erreurs, veuillez contrôler le(s) champs suivant(s) : <b>" . implode(', ', $labels) . "</b>.",
        ));
      }

    }
  }

  /**
   * Instance de modèle `members`
   *
   * @return Array
   */

  protected $_member;

  protected function _member()
  {
    if ( ! $this->_member)
    {
      $this->_member  = ORM::factory('Member', Request::current()->param('id'));
    }

    return $this->_member;
  }

  /**
   * Instance protégé
   *
   * @return Array
   */

  public function member()
  {
    return $this->_member()->as_array();
  }

  /**
   * Peupler le formulaire principal
   *
   * @todo    Devrais être accèssible via `Model_Member::form()`
   * @return  Array
   */

  public function form()
  {
    return $this->form_member(
      $this->member(),
      $this->_notifications,
      $this->_errors
    );
  }

}
