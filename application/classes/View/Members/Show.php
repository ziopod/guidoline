<?php

/**
* Fiche Membre
*
* @package    Guidoline
* @category   View Model
* @author     Ziopod | ziopod@gmail.com
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class View_Members_Show extends View_Master {

	/**
	 * Adhérent courant
   *
   * @return Array
   */

	public function member()
	{
		return ORM::factory('Member', Request::initial()->param('id'))->as_array();
	}
}
