<?php
/**
 * Le modèle de vue `View/Dashboard.php` fournis les propriétés et méthodes pour le template `templates/dashboard.mustache`.
 *
 * @package    Guidoline
 * @category   View Model
 * @author     Ziopod | ziopod@gmail.com
 * @copyright  BY-SA 2013 Ziopod
 * @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
 */


class View_Dashboard extends View_Master {

	/**
	 * @var String Titre pour le tableau de bord
	 */
	public $title = "Guidoline — Tableau de bord";

  /**
   * Tableau d'une insance de modèle `members`
   *
   * @return Array
   */

  protected $_member;

  function member()
  {
    if ( ! $this->_member)
    {
      $this->_member  = ORM::factory('Member')->as_array();
    }

    return $this->_member;
  }

  /**
   * Peupler le formualire principal
   *
   * @todo    Pourrais être automatisé en utilisant `model::labels()`,
   *          `model::names() et `model::keys()` pour peupler les clefs.
   * @return Array
   */

  public function form()
  {
    return $this->form_member($this->member());
  }

  /**
	 * Les derniers 10 membres
	 *
	 * @return  Array
	 */
  protected $_members_last_created;

	public function members_last_created()
	{
    if ( ! $this->_members_last_created)
    {
      $this->_members_last_created = ORM::factory('Member')->last_created();
    }

    return $this->_members_last_created;

	}
}
