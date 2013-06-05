<?php

/**
* Le modèle de vue `View/App/Login.php` fournis les propriétés et méthodes pour le template `templates/app/login.mustache`.
* 
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_App_Login extends View_Layouts_Simple{


	public function __construct()
	{
		parent::__construct();
		$this->title = "Bienvenue sur Guidoline, veuillez vous authentifier";

		if (HTTP_REQUEST::POST === Request::initial()->method())
		{
			$this->post = Request::initial()->post();
			$login = Auth::instance()->login($this->post['username'], $this->post['password'], @$this->post['remember']);
			echo Debug::vars($this->post);
			echo Debug::vars(Kohana::$config->load('auth.users'));
			echo Debug::vars(Auth::instance()->hash_password($this->post['password']));
			echo  View::factory('profiler/stats');
			if ($login)
			{
				$requested_uri = Session::instance()->get('requested_uri');
				Session::instance()->delete('requested_uri');

				if ( ! $requested_uri)
				{
					$requested_uri = Route::get('default')->uri();
				}

//				HTTP::redirect($requested_uri);
			}
			else
			{
				$this->errors['username'] = "Le nom d'utilisateur ne correspont pas au mot de passe saisie";
			}

			//echo Debug::vars($this->errors);
		}
	}
} 