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
  'filter' => '(actifs|inactifs|tous)',
  'folio' => '\d+',
  ))
  ->filter(function($route, $params, $request) {
  })
  ->defaults(array(
    'controller'  => 'MVVM',
    'action'      => 'instanciate',
    'view'        => 'Members/Index',
    'folio'       => 1,
    'filter'      => 'tous'
  ));

 /**
 * Édition d'un adhérent
 */
Route::set('member.edit', 'adherents/edit(/<member_id>)', array(
  'member_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM',
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
  'controller'  => 'MVVM',
  'action'      => 'instanciate',
  'view'        => 'Members/Card',
));

/**
 * Affichage des bulletins d'adhésions
 */
Route::set('forms', 'bulletins')
  ->defaults(array(
    'controller'  => 'MVVM',
    'action'      => 'instanciate',
    'view'        => 'Forms/Index'
  ));

/**
 * Édition d'un bulletin d'adhésion
 */
Route::set('form.edit', 'bulletins/edit(/<form_id>)', array(
  'form_id' => '\d+',
))
->defaults(array(
  'controller'  => 'MVVM',
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
  'controller'  => 'MVVM',
  'action'      => 'instanciate',
  'view'        => 'Forms/Detail',
));

/**
 * About page
 */
Route::set('page.about', 'a-propos')
->defaults(array(
  'controller' => 'MVVM',
  'action'     => 'instanciate',
  'view'       => 'Page/Default',
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
    // /!\ Attention, il faut que le contrôleur MVVM (ou le modèle de vue?)
    // modifie le `content-type` de la réponse HTTP.
    $params['layout'] = 'layouts/json';
  }

  return $params;
})
->defaults(array(
  'controller' => 'MVVM',
  'action'     => 'instanciate',
  'view'       => 'Dashboard',
  'layout'     => 'layouts/default',
  'format'     => 'html',
));
