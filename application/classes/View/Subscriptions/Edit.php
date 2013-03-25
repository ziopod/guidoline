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
	* @vars		object	Adhésion courante
	**/
	public $subscription;

	/**
	* L'édition d'une adhésion génère t'elle une erreur
	*
	* @return	boolean
	**/
	public function has_errors()
	{
		return (bool) $this->errors();
	}

}