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
		//
// http://www.datatables.net/release-datatables/examples/server_side/server_side.html
// http://www.datatables.net/release-datatables/examples/ajax/objects.html
// http://www.datatables.net/release-datatables/examples/ajax/objects_subarrays.html

// http://datatables.net/usage/server-side
// http://datatables.net/forums/discussion/5331/datatables-warning-...-requested-unknown-parameter/p1

//		echo Debug::vars($this->request->query());
//		echo Debug::vars($this->request->post());
		if ($this->request->param('format') == 'json')
		{
			$this->request->headers('Content-Type', 'application/json; charset='.Kohana::$charset);
			$members = ORM::factory('Member')
				->limit($this->request->param('iDisplayStart'), $this->request->param('iDisplayLength'));
			$total_count =1;// (int) $members->count_all();
// 			$members = DB::select('id', 'created', 'name', 'firstname', 'email', 'cellular', 'street', 'zipcode', 'city')
// 				->from('members')
// 				->limit($this->request->param('iDisplayStart'), $this->request->param('iDisplayLength'))
// //							->order_by($this->request->param())
// //							->where()
// 				->execute()->as_array();
			$dm = array();
			$base_url = URL::base(FALSE, TRUE);
			//foreach ($members->find_all() as $member)
			foreach ($members->find_all() as $member)
			{
				$subscriptions = '';

				foreach ($member->last_valid_subscriptions() as $subscription_member)
				{
					$subscription_members .= $subscription_member->subscription->title . 'Inscrit le ' . $subscription_member->start_date . ' (' . $subscription_member->elapsed_time_fuzzy . ')'.
						'périmé le '. $subscription_member->remaining_time . ' (le ' . $subscription_member->end_date .')';
					// if ($subscription_member->valid_subscription())
					// {
					// 	$subscription_members .= $subscription_member->subscription->title . 'Inscrit le ' . $subscription_member->start_date . ' (' . $subscription_member->elapsed_time_fuzzy . ')'.
					// 		'périmé le '. $subscription_member->remaining_time . ' (le ' . $subscription_member->end_date .')';
					// }
					// else
					// {
					// 	$subscription_members .= $subscription_member->subscription->title . 'Périmé depuis le ' . $subscription_member->end_date . ' (' . $subscription_member->end_date_fuzzy . ')';
					// }
				}

				$subscriptions .= '<a href="'.$base_url.'members/subscriptions/'.$member->id.'" class="icon-cog icon-2x tip" title="Gérer les inscriptions"></a>';

				$dm[] = array(
					'#' . $member->id,
					$member->firstname . ' ' . $member->name,
					$member->birthdate,
					$member->created,
					// $member->status->name,
					$member->email,
					$member->cellular,
					$member->city,
					$subscriptions,
					'<a class="icon-pencil icon-2x modale tip" href="'.$base_url.'members/edit/'.$member->id.'#form_content" title="Modifier"></a>'
				);
			}



			$response = Json_encode(
				array(
					"sEcho"	=> (int) $this->request->param('sEcho'),
					"iTotalRecords" => $total_count,
					"iTotalDisplayRecords"	=> $total_count,
					"aaData"	=> $dm,
				)
			);

			$this->response->body($response);
			return;
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
	* Ajouter une adhésion
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