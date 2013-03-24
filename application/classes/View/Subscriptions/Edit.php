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

class View_Subscriptions_Edit extends View_Layout {
	
	/**
	* @vars		string	Le titre de la page
	**/
	public $title;

	/**
	* @vars		array	Les erreurs de validation du formulaire
	**/
	public $errors;

	/**
	* @vars		string Message de succes ou d'erreur
	**/
	public $message;

	/**
	* Affiche l'adhésion à ajouter ou à modifer, ajoute ou enregistre l'adhésion
	**/

	public function subscription()
	{
		$subscription = ORM::factory('subscription', Request::initial()->param('id'));
		$post = Request::initial()->post();

		if ( ! empty($post))
		{
			$subscription->values($post);
		
			try
			{
				$this->message = $subscription->loaded() ? "Modification réussi" : "Ajout réussi";
				$subscription->save();
				// Enregister le message en variable de session ou cookie, puis redirection ?
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->message = $subscription->loaded() ? "Echec de la modification" : "Echec de l'ajout";
				$this->errors = $e->errors('models');
			}
		}

		return $subscription;

	}
}