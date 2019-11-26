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
   * Contrôler l'édition possible de l'adhérent
   * Recevoir les données post et sauvegarder les données
   */
  public function __construct()
  {
    parent::__construct();

    $is_new = ! $this->_orm_member()->loaded();

    // Adhérent éligible à l'édition
    if ($is_new && Request::current()->param('id') )
    {
      throw new HTTP_Exception_404("L'adhérent (ID: :id) demandé, n'as pas été trouvé.", array(':id' => Request::current()->param('id')));
    }

    // Enregistrement des données
    if (Request::current()->method() === HTTP_Request::POST)
    {
      // Flatten address
      $post = Request::current()->post();
      $post = array_merge($post, $post['address']);
      unset($post['address']);

      // Save model
      $this->_orm_member()
      ->values(
        $post,
        array(
          'idm',
          'lastname',
          'firstname',
          'gender',
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
        $this->_orm_member()
        ->save();

        // Enregistrement d'une adhésion
        $member_forms = Arr::get($post, 'member_forms', array());
        // Les adhésions à enregistrer
        $member_forms_to_add = array();

        foreach ($member_forms as $key => $member_form_id)
        {
          $member_form_id = (Integer) $member_form_id;
          $member_form = ORM::factory('Form', $member_form_id);

          if ( ! $this->_orm_member()->has('forms', array($member_form_id)))
          {
            $member_forms_to_add[] = $member_form;
            continue;
          }

          // $form_is_active =
          if ( ! $this->_orm_member()->forms
            ->where(DB::expr('`form`.`id`'), '=', $member_form_id)
            ->find()
            ->dues
            ->where('member_id', '=', $this->_orm_member()->pk())
            ->and_where('is_active', '=', 1)
            ->loaded())
          {
            $member_forms_to_add[] = $member_form;
          }
        }

        foreach ($member_forms_to_add as $member_form)
        {
          // On ne peux pas utiliser ORM::add()
          ORM::factory('Due')
          ->values(array(
            'member_id' => $this->_orm_member()->pk(),
            'form_id' => $member_form->pk(),
            'amount' => $member_form->price,
            'currency_code' => $member_form->currency_code,
            'date_start' => $member_form->date_start(),
            'date_end' => $member_form->date_end(),
          ))
          ->save();
          // $f = $this->_orm_member()->add('forms', $member_form);
          // $member_form->due->set('amount', $member_form->price)->save();
          // echo Debug::vars($member_form->due);
        }

        // echo Debug::vars($member_forms_to_add);
        // Enregistrement des métas

        $this->_notifications[] = array('notification' => array(
          'kind' => 'success',
          'content' => ($is_new ? "Ajout" : "Modifications") . " de la fiche de <b>{$this->_orm_member()->fullname()}</b> effectué.",
        ));
      }
      catch (ORM_Validation_Exception $e)
      {
        // $this->_orm_member = $this->_orm_member();
        $this->_html_form_errors = $e->errors('models');
        $labels = array_intersect_key(
            $this->_orm_member()->labels(),
            array_flip(array_keys($this->_html_form_errors))
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

  protected $_orm_member;

  protected function _orm_member()
  {
    if ( ! $this->_orm_member)
    {
      $this->_orm_member  = ORM::factory('Member', Request::current()->param('member_id'));
    }

    return $this->_orm_member;
  }

  /**
   * Instance protégé
   *
   * @return Array
   */

  protected $_member;

  public function member()
  {
    if ( ! $this->_member)
    {
      $this->_member =  $this->_orm_member()
      ->as_array('genders,forms_all,forms,dues');
    }

    return $this->_member;
  }

  /**
   * Peupler le formulaire principal
   *
   * @todo    Devrais être accèssible via `Model_Member::form()`
   * @return  Array
   */

  public function form()
  {
    if ( ! $this->_html_form)
    {
      $this->_html_form = $this->_orm_member()->html_form($this->_html_form_errors);
    }

    return $this->_html_form;
  }

  public function notifications()
  {
    return $this->_notifications;
  }
}
