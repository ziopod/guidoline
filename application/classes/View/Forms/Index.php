<?php

/**
* Modèle de vue pour l'affichage de l'index des adhésion `templates/subscirptions/index.mustache`
*
* @package		Guidoline
* @category		View Model
* @author		Ziopod | ziopod@gmail.com
* @copyright	BY-SA 2013 Ziopod
* @license		http://creativecommons.org/licenses/by-sa/3.0/
*/

class View_Forms_Index extends View_Master {

	/**
	* @vars		Title	Le titre de la page
	**/
	public $title = "Adhésions - Guidoline";

  /**
   * @var Integer   $_limit   Limite pour la requête
   */
  protected $_limit = 100;

  /**
   * @var Array $_forms   Stockage des bulletins d'adhésion
   */
  protected $_forms;

  /**
   * Le filtre coutant
   *
   * @return String
   */
  public function current_filter()
  {
    return Request::current()->param('filter');
  }

	/**
	 * Les bulletins d'adhésion
   *
   * @return Array
	 */
	public function forms()
	{
    if ( ! $this->_forms)
    {
      $this->_forms = array(
        'records' => array(),
        'records_count' => $this->_forms_query()->count_all(),
        'total_count' => 0,
        'paginate' => array(),
      );

      // Pagination
      $this->_forms['paginate'] = (new Paginate(array(
        'url_prefix' => URL::site(
          Route::get('forms')->uri(array(
            'filter' => $this->current_filter()
          )),
          TRUE) . '/'
        )))
        ->create(
          Request::current()->param('folio'),
          $this->_limit,
          $this->_forms['total_count']
        );

      // Trouver tous le bulletins
      $forms = $this->_forms_query()
        ->offset($this->_forms['paginate']['offset'])
        ->limit($this->_forms['paginate']['limit'])
        ->find_all();

      foreach( $forms as $form)
      {
        $this->_forms['records'][]['form'] = $form->as_array('dues_all');
      }

      $this->_forms['records_count'] = count($this->_forms['records']);
    }

		return $this->_forms;
  }

  /**
   * Requête pour les bulletins d'adhésions
   *
   * @return ORM
   */

  protected function _forms_query()
  {
    $filters = array(
      'actifs' => 1,
      'inactifs' => 0
    );

    $is_active = Arr::get($filters, Request::current()->param('filter'));
    $forms = ORM::factory('Form');

    if ($is_active !== NULL)
    {
      $forms->where('is_active', '=', $is_active);
    }

    return $forms;
  }
}
