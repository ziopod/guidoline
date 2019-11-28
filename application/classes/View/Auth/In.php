<?php

class View_Auth_In extends View_Master {

  public $layout = 'layouts/simple';
  public $errors = array();

  public function html_form()
  {
    return array(
      'title'   => 'Connexion',
      'action'  => URL::site(Route::get('default')->uri(), TRUE),
      'form_id' => 'login',
      'data'    => array(
        'email' => array(
          'field' => array(
            'label' => __('Email utilisateur'),
            // 'help'     => 'Saississez votre adresse email pour recevoir votre code d\'authentification.',
            'placeholder' => 'jcvd@example.com',
            'required'    => 'required',
            'name'        => 'email',
            'id'          => 'email',
            'value'       => Request::current()->post('email'),
            'class'       => 'is-large',
            'error' => Arr::get($this->errors, 'email'),
          )
        ),
        'secret' => array(
          'field' => array(
            'label' => __('Code de connexion'),
            'help'  => 'Code de 6 chiffre envoyé par email.',
            'name'  => 'secret',
            'id'    => 'secret',
            'class' => 'is-large',
            'value' => Request::current()->post('secret'),
            'error' => Arr::get($this->errors, 'secret'),
          ),
        )
      )
    );
  }
}
