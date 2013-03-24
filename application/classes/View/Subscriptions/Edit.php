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
	* @vars 	string	L'adhésion à ajouter ou modifier
	**/
//	public $subscription;

	/**
	* @vars		array	Les erreurs de validation du formulaire
	**/
	public $errors;

	public function subscription()
	{
		$subscription = ORM::factory('subscription', Request::initial()->param('id'));
		$post = Request::initial()->post();
var_dump($post);
		if ( ! empty($post))
		{
			$subscription->values($post);
			try
			{
				$subscription->save();
			}
			catch (ORM_Validation_Exception $e)
			{
				$this->errors = $e->errors('models');
			}
		}

		return $subscription;

	}
}