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

		if ($this->request->is_ajax() OR $this->request->param('format') === 'json')
		{
			//echo Debug::vars($_GET);

			$data = array(
				'total_count' 	=> NULL,
				'members'		=> array(),
			);

			$this->request->headers('Content-Type', 'application/json; charset='.Kohana::$charset);
			$members = ORM::factory('Member')
				->offset($this->request->query('offset'))
				->limit($this->request->query('limit'));

			$data['total_count'] = $members->reset(FALSE)->count_all();

			foreach ($members->find_all() as $member) {

				$subscriptions = array();

				foreach (ORM::factory('Subscription')->find_all() as $subscription)
				{
					$formatted_subscription = array(
						'slug'					=> $subscription->slug,
						'title'					=> $subscription->title,
						'id'					=> $subscription->id,
						'membership'			=> FALSE, // TRUE / FALSE
						'valid_subscription'	=> FALSE, // TRUE / FALSE

					);

					if ($member->has_any('subscriptions', $subscription))
					{
						$formatted_subscription['membership'] = TRUE;
						$valid =  $member->subscriptions_members->where('subscription_id', '=', $subscription->id)->find()->valid_subscription;

						if ( $valid)
						{
							$formatted_subscription['valid_subscription'] = TRUE; 
						}

					}

					$subscriptions[]['subscription'] = $formatted_subscription;
					//$subscriptions[] = $formatted_subscription;
				}

				$data['members'][] = array(
					'id'				=> $member->id,
					'idm'				=> $member->idm,
					'firstname' 		=> $member->firstname,
					'name'				=> $member->name,
					'fancy_birthdate'	=> $member->fancy_birthdate,
					'fancy_created'		=> $member->fancy_created,
					'email'				=> $member->email,
					'cellular'			=> $member->cellular,
					'city'				=> $member->city,
					'country'				=> $member->country,
					'job'				=> $member->job,
					'subscriptions'		=> $subscriptions
				);

			}

			$response = Json_encode($data);
			$this->response->body($response);
		}
		else
		{
			$view = new View_Members_Index;
			$this->response->body($this->layout->render($view));
		}

	}

	/**
	* Afficher le profil utilisateur
	**/
	public function action_profile()
	{
		$view = new View_Members_Profile;
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
				
				if (Arr::get($post, 'redirect'))
					HTTP::redirect(Route::get('default')->uri());

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
		$uri = Route::get('default')->uri(array('controller' => 'members', 'action' => 'index'));
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
	* Ajout rapide d'une inscritpion
	**/
	public function action_subscriptions_quickadd()
	{
		$member = ORM::factory('Member', $this->request->param('member_id'));
		// echo Debug::vars($member->loaded());
		$member->add('subscriptions', ORM::factory('Subscription', $this->request->param('subscription_id')));
		HTTP::redirect($this->request->referrer());

	}

	/**
	* Ajouter une inscription
	**/
	public function action_subscriptions_add()
	{
		$post = $this->request->post();

		if ( ! empty($post))
		{
			$member = ORM::factory('Member', $this->request->param('id'));
			$member->add('subscriptions', ORM::factory('Subscription', $post['subscription_id']));

			if ($this->request->is_ajax())
			{
				return TRUE;
			}

			HTTP::redirect(Route::get('default')->uri(array('controller' => 'members', 'action' => 'subscriptions')) . '/' . $this->request->param('id'));
		}

		$view = new View_Members_Subscriptions_Add;
		$this->response->body($this->layout->render($view));
	}

	/**
	* Supprimer une adhésion
	**/
	public function action_subscription_delete()
	{
		$subscription = ORM::factory('Subscriptions_Member', $this->request->param('id'));

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