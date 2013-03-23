<?php defined('SYSPATH') or die ('No direct script access');

/**
* Members, pour gérer les membres de l'association
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_Members extends Controller_App {

	/**
	* Affiche l'index des membres
	*
	* @return void
	**/
	public function action_index()
	{
		$view = new View_Members_Index;

		if ($this->request->param('format') == 'json')
		{
			$this->request->headers('Content-Type', 'application/json; charset='.Kohana::$charset);
			$this->response->body(Json_encode(DB::select('id', 'firstname', 'name', 'email')->from('members')->execute()->as_array()));
			return;
		}

		$this->response->body($this->layout->render($view));

	}

	/**
	* Afficher le profil utilisateur
	**/
	public function action_profil()
	{
		$view = new View_Members_Profil;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Ajouter ou modifier un membre
	*
	* Essai avec tout dans le modèle view
	**/
	public function action_edit()
	{
		$id = Request::initial()->param('id');
		$member = ORM::factory('Member', $id);
		$this->_show_edit_form($member);
	}

	/**
	* Sauvegarde un membre
	**/
	public function action_save()
	{
		$post = $this->request->post();

		if (!empty($post))
		{
			$member = ORM::factory('Member', $post['id'])->values($post);

			try
			{
				$member->save();
				$this->_redirect_to_list();
			}
			catch (ORM_Validation_Exception $e)
			{
				$errors = $e->errors('models');
			}
		}

		$this->_show_edit_form($member, $errors);
	}

	/**
	* Utilitaire pour afficher le formulaire
	**/
	private function _show_edit_form($member, $errors = NULL)
	{
		$view = new View_Members_Edit;
		$view->original_values = $member->original_values();
		$view->member = $member;
		$view->errors = $errors;
		$view->title = $member->loaded() ? "Modifier la fiche membre de {$view->original_values['firstname']} {$view->original_values['name']}" : "Ajouter un membre";
		$this->response->body($this->layout->render($view));

	}

	/**
	* Redirection vers la liste de membres
	**/
	private function _redirect_to_list()
	{
		$uri = Route::get('default')->uri(array('controller' => 'members', 'action' => $action));
		HTTP::redirect($uri);
	}

	/**
	* Affiche la page de gestion des adhésion pour le membre
	**/
	public function action_subscriptions()
	{
		$view = new View_Members_Subscriptions_Index;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Ajouter une adhésion
	**/
	public function action_subscriptions_add()
	{
		$post = $this->request->post();

		if ( ! empty($post))
		{
			$member = ORM::factory('member', $this->request->param('id'));
			$member->add('subscriptions', ORM::factory('subscription', $post['subscription_id']));
			$this->_redirect_to('subscriptions');
		}

		$view = new View_Members_Subscriptions_Add;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Supprimer une adhésion
	**/
	public function action_subscription_delete()
	{
		$subscription = ORM::factory('subscriptions_member', $this->request->param('id'));

		if ($subscription->loaded())
		{
			$subscription->delete();

			if ($this->request->is_ajax())
			{
				return TRUE;
			}

			HTTP::redirect($this->request->referrer());

		}
		else
		{
			echo '— Aucune adhésion chargée —';
		}
	}
}