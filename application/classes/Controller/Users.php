<?php defined('SYSPATH') or die ('No direct script access');

/**
* Le contrôleur "Users" est utilisé pour gérer les propiétés et les méthodes liés aux utilistateurs.
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_Users extends Controller_App {

	/**
	* Affiche l'index des utilisateurs
	*
	* @return  void
	**/
	public function action_index()
	{
		$view = new View_Users_Index;
		// $users = DB::select('username', 'email')->from('users')->execute();
		// $result = (object) NULL;
		// $result->users = $users->as_array();
		// $result->users_count = count($users);
		// return $result;
		/* La base de données n'est pas encore en place, nous utiliserons le tableau suivant à la place */
		$DB =  array(
			array(
				'id'		=> 1,
				'firstname'	=> 'Bertrand',
				'name'		=> 'Keller',
				'address'	=> 'rue du Sacre',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Compositeur',
				'email'		=> 'bertrand@example.com',
				),
			array(
				'id'		=> 2,
				'firstname'	=> 'Steve',
				'name'		=> 'Beau',
				'address'	=> 'place de la rougemare',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Orthographeur',
				'email'		=> 'steve@example.com',
				),
			array(
				'id'		=> 3,
				'firstname'	=> 'Alexandre',
				'name'		=> 'Ronsault',
				'address'	=> '1, place du docteur Alfred Cerné',
				'zipcode'	=> '76000',
				'city'		=> 'ROUEN',
				'job'		=> 'Contemporain',
				'email'		=> 'alexandre@example.com',
				),

			);
		$data = array();
		


		if ($this->request->param('format') == 'json')
		{
			// Json
			$this->request->headers('Content-Type', 'application/json; charset='.Kohana::$charset);
			/* Les headers HTTP ne sont pas corrects, à corriger */
			//$this->request->body(json_encode($users));
			//$this->response->send_headers();
			$this->response->body(Json_encode(DB::select('id', 'username', 'email')->from('Users')->execute()->as_array()));
			return;
		}

		$this->response->body($this->layout->render($view));
	}

	/**
	* Ajout rapide d'un nouvel utilisateur
	**/
	public function action_register()
	{

	}

	/**
	* Ajouter ou modifier un utilisateur
	**/
	public function action_edit()
	{
		$id =$this->request->param('id');
		$user = ORM::factory('User', $id);
		$this->_show_edit_form($user);

	}

	/**
	* Afficher le formulaire d'édition
	**/
	private function _show_edit_form($user, $errors = NULL)
	{
		$view = new View_Users_Edit;
		$original_values = $user->original_values();
		$view->title = $user->loaded() ? "Modifier la fiche de {$original_values['username']}" : "Ajouter un nouvel utilisateur";
		$roles = array();

		foreach (DB::select()->from('roles')->execute() as $role)
		{
			//if ($user->has('roles', $role['id'])) // tout les rôles
			if ($user->has('roles'))
			{
				if ($user->role->id == $role['id']) // Le rôle le plus haut
				{
					$role['selected'] = TRUE;
				}
			}
			$roles[] = $role;
			//echo Debug::vars($role);
		}

		$view->roles = $roles;
		//$view->user = $user->as_array();
		$view->user = $user; // Sans as_array(), Permet d'appeler les méthodes du modèle dans les templates?
		$view->errors = $errors;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Sauvegarder ou ajouter un nouvel utilisateur
	**/
	public function action_save()
	{
		$post = $this->request->post();

		if (count($post))
		{
			$user = ORM::factory('User', $post['id'])->values($post);

			try
			{
				if (! $user->password)
				{
					$user->password = TEXT::random(NULL, 64);
				}
				$user->save();
				$this->_redirect_to_list();
			}
			catch (ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');
			}
			
			// echo Debug::vars($errors);
			$this->_show_edit_form($user, $errors);
		}
	}

	/**
	* Afficher le profil
	**/
	function action_profil()
	{
		$view = new View_Users_Profil;
		$view->user = ORM::factory('user', $this->request->param('id'));
		$this->response->body($this->layout->render($view));
	}

	/**
	* Clean redirection
	**/
	private function _redirect_to_list()
	{
		// $session = Session::instance();
		// $query = $session->get('mes_session_vars'); // Permet de récup les vars en _GET
		$uri = Route::get('default')->uri(array('controller' => 'users', 'action' =>'index')); // . URL::query($query); // Ajouter les vars en _GET à l('url')
		HTTP::redirect($uri);
	}
}