<?php

/**
 * Tentons de tout contrôler depuis les modèles de vue
 */

class Controller_MVVM extends Controller {

  /**
  * @var Kostache_Layout
  **/
  public $layout;

  /**
  * @var View
  **/
  public $view;

  /**
   *
   * Charge un [Kostache_Layout] et une vue POO.
   * Tente de charger une vue POO basé sur le nom du contrôleur
   * et de l'action demandé.
   *
   * @throws HTTP_Exception_404
   * @return void
   */
  public function before()
  {
    parent::before();

    if ( ! $this->view)
    {

      $view = $this->request->param('view');
      $view = str_replace(' ', '_', ucwords(str_replace(DIRECTORY_SEPARATOR, ' ', $view)));
      $view = 'View_' . $view;

      if (class_exists($view))
      {
        $this->view = new $view;
      }
      else
      {
        throw new HTTP_Exception_404("View :view not found", array(':view' => $view));
      }
    }

    $layout = isset($this->view->layout) ? $this->view->layout : 'layouts/default';
    $this->layout = Kostache_Layout::factory($layout);
  }

  /**
  * Assigne le rendu de la vue au corps de la réponse de la requête.
  *
  * @return void
  **/
  public function after()
  {
    parent::after();

    if ($this->view->auto_render === TRUE)
    {
      $this->response->body($this->layout->render($this->view));
    }
  }

  /**
   * Extension de la méthode native [Controller::execute()]
   *
   * Supression de la tentative de chargement des méthodes `action_`
   *
   * @return Response
   */
  public function execute()
  {
	  // Execute the "before action" method
	  $this->before();

	  // Execute the "after action" method
	  $this->after();

	  // Return the response
	  return $this->response;
  }
}
