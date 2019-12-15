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
   * Tableau d'une instance de modèle `members`
   *
   * @return Array
   */

  protected $_member;

  function member()
  {
    if ( ! $this->_member)
    {
      $this->_member  = ORM::factory('Member')
      ->as_array('genders,forms_all');
      $this->_member['select_genders'] = ORM::records_to_options($this->_member['genders']);
      // echo Debug::vars($this->_member);
    }
    return $this->_member;
  }

  /**
   * Peupler le formulaire principal
   *
   * @todo    Pourrais être automatisé en utilisant `model::labels()`,
   *          `model::names() et `model::keys()` pour peupler les clefs.
   * @return Array
   */

  public function html_form()
  {
    // echo Debug::vars()
    return ORM::factory('Member')->html_form();
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
      $this->_members_last_created = array(
        'records'       => array(),
        'records_count' => 0,
      );

      foreach (ORM::factory('Member')
        ->order_by('created', 'desc')
        ->limit(10)
        ->find_all() as $member)
      {
        $this->_members_last_created['records'][]['member'] = $member->as_array();
      }

      $this->_members_last_created['records_count'] = count($this->_members_last_created['records']);
    }

    return $this->_members_last_created;

	}
}
