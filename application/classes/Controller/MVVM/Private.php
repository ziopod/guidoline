<?php

/**
 * Gestion des vues privées
 *
 * @todo   Définir des `scopes` via les routes, contrôler le niveau
 *         d'acccès dans ce contrôleur.
 */

class Controller_MVVM_Private extends Controller_MVVM {


  public function before()
  {

    // No logged
    if ( ! Auth::instance()->logged_in('login'))
    {
      if (Request::current()->query('clear_email'))
      {
        Session::instance()->delete('email');
      }

      if ( ! Session::instance()->get('email'))
      {
        // 1. Contrôler l'email et envoyer le secret
        $this->_send_secret();
      }
      else
      {
        // 2. Contrôler le secret
        $this->_check_secret();
      }
    }

    parent::before();

  }

  /**
   * Check email and send secret
   *
   * @return void
   */
  protected function _send_secret()
  {
    // Overwrite view
    $this->view = new View_Auth_In;

    if (Request::current()->method() === HTTP_request::POST)
    {
      $email = Request::current()->post('email');

      if ( ! Valid::email($email))
      {
        $this->views->errors['email'] = "Email invalide"; //Kohana::message('models/user', 'email.valid');
        return;
      }

      $auth_user = Model::factory('User')->where('email', '=', $email)->find();

      if ( ! $auth_user->loaded())
      {
        $this->view->errors['email'] = "Email utilisateur inconnu"; //Kohana::message('models/user', 'email.unregistered');
        return;
      }

      $secret = Text::random('numeric', 6);
      Session::instance()->set('secret_hash', Auth::instance()->hash($secret));
      $auth_user->send_email(array(
        'secret'        => $secret,
        'email_user'    => $auth_user->email,
        'email_contact' => Kohana::$config->load('guidoline.emails.contact.email'),
        'url_home'      => URL::site(Route::get('default')->uri(), TRUE),
      ), 'signin');

      Session::instance()->set('email', $email);

      // Vue pour la saisie du secret
      $this->view = new View_Auth_In_Validate;

    }
  }

  protected function _check_secret()
  {

    // Contrôle du secret
    if (Session::instance()->get('secret_hash') === Auth::instance()->hash(Request::current()->post('secret')))
    {
      Auth::instance()->force_login(Session::instance()->get('email'));
      return;
    }

    // Overwrite view
    $this->view = new View_Auth_In_Validate;

    // Echec
    $this->view->errors['secret'] = 'Code invalide. <a href="/?clear_email=true">recommencer la procédure ?</a>'; //Kohana::message('auth', 'secret.wrong');
  }

  protected $_errors;

  public function form()
  {
    return array(
      'email' => array(
        'value'     => Request::current()->post('email'),
        'required'  => 'required',
        'error'     => Arr::get($this->_errors, 'email'),
      )
    );
  }
}
