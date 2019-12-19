<?php
/**
 * Toutes les routes
 */

/**
* Affichage des adhérents
*
* GET  /(<filter>)      : Activité des adhérents
* GET  /<folio>         : Pagination
*/
Route::set('members', 'adherents(/<filter>)(/<folio>)', array(
  'filter' => '(actifs|inactifs|benevoles)',
  'folio' => '\d+',
  ))
  ->filter(function($route, $params, $request) {
  })
  ->defaults(array(
    'controller'  => 'MVVM_Private',
    'action'      => 'instanciate',
    'view'        => 'Members/Index',
    'folio'       => 1,
    'filter'      => '',
  ));

 /**
 * Édition d'un adhérent
 */
Route::set('member.edit', 'adherents/edit(/<member_id>)', array(
  'member_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM_Private',
  'action'      => 'instanciate',
  'view'        => 'Members/Edit',
));

/**
* Affichage d'un adhérent
*/
Route::set('member.detail', 'adherents/detail/<member_id>', array(
  'member_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM_Private',
  'action'      => 'instanciate',
  'view'        => 'Members/Card',
));

/**
 * Affichage des bulletins d'adhésions
 */
Route::set('forms', 'bulletins(/<filter>)(/<folio>)', array(
  'filter' => '(actifs|inactifs)',
  'folio'  => '\d+',
  ))
  ->defaults(array(
    'controller'  => 'MVVM_Private',
    'action'      => 'instanciate',
    'view'        => 'Forms/Index',
    'folio'       => 1,
    'filter'      => 'actifs',
  ));

/**
 * Édition d'un bulletin d'adhésion
 */
Route::set('form.edit', 'bulletins/edit(/<form_id>)', array(
  'form_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM_Private',
  'action'      => 'instanciate',
  'view'        => 'Forms/edit'
));

 /**
  * Affichage d'un bulletin d'adhésion
  */
Route::set('form.detail', 'bulletins/detail/<form_id>', array(
  'form_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM_Private',
  'action'      => 'instanciate',
  'view'        => 'Forms/Detail',
));

/**
 * Détail d'une adhésion
 */
Route::set('due.detail', 'dues/detail/<due_id>', array(
  'due_id'  => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM_Private',
  'action'      => 'instanciate',
  'view'        => 'Dues/Detail',
));

/**
 * About page
 */
Route::set('page.about', 'a-propos')
->defaults(array(
  'controller' => 'MVVM_Private',
  'action'     => 'instanciate',
  'view'       => 'Page/Default',
));

/**
 * Logout page
 */
Route::set('auth.logout', 'deconnexion')
->filter(function($route, $params, $request) {
  Auth::instance()->logout();
  Session::instance()->destroy();
  // /!\ redirect sur 301 not auth
})
->defaults(array(
  'controller' => 'MVVM_Private',
  'action'     => 'instanciate',
  'view'       => 'Dashboard',
));

// Defaults
// Route::set('default_controller', '(<controller>(/<action>(/<id>)))')
//   ->defaults(array(
//     'controller' => 'Dashboard',
//     'action'     => 'index',
//   ));


/**
 * API
 */
Route::set('action', 'action/<do>(/<id>)')
->defaults(array(
  'controller' => 'MVVM_Private',
  'action'     => 'instanciate',
  'view'       => 'Action',
));

// Tente de capturer une vue par défaut
// Un format de rendu peut être choisi
// Une vue non trouvée, généreras une erreur HTTP 404
Route::set('default', '(<view>)(.<format>)',
array(
  // 'view' => '.*',
  'view' => '^[^\.^?]*',
  'format' => '(json|html)',
))
->filter(function($route, $params, $request) {
  if ($params['format'] === 'json')
  {
    // /!\ Attention, il faut que le contrôleur MVVM_Private (ou le modèle de vue?)
    // modifie le `content-type` de la réponse HTTP.
    $params['layout'] = 'layouts/json';
  }

  return $params;
})
->defaults(array(
  'scope'      => 'login',
  'controller' => 'MVVM_Private',
  'action'     => 'instanciate',
  'view'       => 'Dashboard',
  'layout'     => 'layouts/default',
  'format'     => 'html',
));
