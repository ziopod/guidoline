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

class View_Members_Profil extends View_Layout {

	/**
	* Retourne la fiche membre
	*
	* Cette méthpde est-elle plus a sa place dans le contrôleur ou le modèle de vues?
	* Cf. Controller_Users::action_profil()
	* À prioris sue MVVM, elle le maximum de méthodes doit ce trouver dans le modèle de vue
	* Egalement faire attention à  : pas de formatage de données dans les contrôleur, déporter plutôt vers le modèle
	* Cf. http://jtreminio.com/2012/04/getting-started-with-kohana-3-part-iii-controller-mvvm-kostache/
	* Cf. https://github.com/zombor/Auto-Modeler
	**/
	public function member()
	{
		return ORM::factory('Member', Request::initial()->param('id'));
	}
}