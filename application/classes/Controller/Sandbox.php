<?php defined('SYSPATH') or die ('No direct script access');

/**
* Pour faire tout plein de tests sans pourrir le reste de l'app.
*
* @package    Guidoline
* @category   Controller
* @author     Ziopod
* @copyright  BY-SA 2013 Ziopod
* @license    http://creativecommons.org/licenses/by-sa/3.0/deed.fr
*/

class Controller_Sandbox extends Controller_App {

	/**
	* L'action lié à la vue par défaut `View/Sandbox/Index.php`
	*
	* @return  void
	**/
	public function action_index()
	{
		$this->response->body($this->layout->render(new View_Sandbox_Index));
	}
}