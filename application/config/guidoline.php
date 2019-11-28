<?php defined('SYSPATH') OR die('No direct script access.');

/**
* Globale public informations and configurations
**/

return array(
	/**
	* Default Guidoline application name
	**/
	'name'		=> 'Guidoline Rouen',
  'version' => 'major.minor.correctif',
	/**
	* Restrict API to HTPS
	**/
  'use_tsl' => FALSE,
  /**
   * Emails
   */
  'emails' => array(
    'contact' => array(
      'email' =>'ziopod@gmail.com',
      'name'  => 'Ziopod',
    ),
  )
);
