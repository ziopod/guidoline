<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

/**
 * @todo Lecture https://12factor.net/fr/
 */

// Composer autoloader
// require INSTALL_PATH . 'vendor/autoload.php';
require APPPATH . '../vendor/autoload.php';

// Load the core Kohana class
require SYSPATH.'classes/Kohana/Core'.EXT;

if (is_file(APPPATH.'classes/Kohana'.EXT))
{
  // Application extends the core
  require APPPATH.'classes/Kohana'.EXT;
}
else
{
  // Load empty core extension
  require SYSPATH.'classes/Kohana'.EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set('Europe/Paris');

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale(LC_ALL, 'fr_FR.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
//spl_autoload_register(array('Kohana', 'auto_load_lowercase'));


/**
 * Define VERSION constante from  `.version` file
 */

// $version_file = new SplFileObject(DOCROOT . '.version');
$version_file = new SplFileObject(APPPATH . '../public/.version');

if ($version_file->isFile())
{
  $version = $version_file->fgets();
}
else
{
  $version = 'undefined';
}

define('VERSION', $version);

/**
 * Load Dotenv
 *
 * - File `.env` to rootproject are required
 * - `ENVIRONMENT` dotenv variable are required with one of
 *   theses values : 'production', 'staging', 'testing', 'development'
 */

try
{
  // $dotenv = Dotenv\Dotenv::create(INSTALL_PATH);
  $dotenv = Dotenv\Dotenv::create(APPPATH . '../');
  $dotenv->load();
}
catch (Exception $e)
{
  throw new Exception($e->getMessage());
}

try
{
  $dotenv->required('COOKIE_SALT');
  $dotenv->required('ENVIRONMENT')->allowedValues(['production', 'staging', 'testing', 'development']);
}
catch(Exception $e)
{
  throw new Exception($e->getMessage());
}

// Set environment value
$_SERVER['KOHANA_ENV'] = getEnv('ENVIRONMENT');

// $dotenv->required(['VAR']);
// $dotenv->required('VAR')->notEmpty();
// $dotenv->required('VAR')->isInteger();
// $dotenv->required('VAR')->isBoolean();
// $dotenv->required('VAR')->allowedValues(['VALUE1', 'VALUE2']);
// $dotenv->required('VAR')->allowedRegexValues('<regex rule>');
// echo Debug::vars($_ENV);

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('fr-fr');

/**
* Set cookie salt
**/
Cookie::$salt = getEnv('COOKIE_SALT');
//Cookie::$httponly = TRUE;
//Cookie::$secure = filter_var(getEnv('COOKIE_SECURE'), FILTER_VALIDATE_BOOLEAN);
//Cookie::$domain = getEnv('COOKIE_DOMAIN');

/**
 * @todo améliorer la sécurité
 * https://koseven.ga/documentation/kohana/security/encryption
 */

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV']))
{
  Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
}

/**
 * Set errors reporting
 */
switch(Kohana::$environment)
{
  case Kohana::PRODUCTION :
    // All minus notice
    error_reporting(E_ALL & ~E_NOTICE);
    ;
  break;
  case Kohana::STAGING :
    // Only strict
    error_reporting(E_ALL & ~E_NOTICE);
    break;
  case Kohana::TESTING :
    // All minus deprecated and notice
    error_reporting(E_ALL | E_STRICT);
    break;
  // development by default
  default:
    // All errors
    error_reporting(E_ALL | E_STRICT);
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - integer  cache_life  lifetime, in seconds, of items cached              60
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 * - boolean  expose      set the X-Powered-By header                        FALSE
 */
Kohana::init(array(
  'base_url'   => getEnv('BASE_URL'),
  'index_file' => FALSE,
  'caching'    => Kohana::$environment === Kohana::PRODUCTION,
  'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
  'errors'     => Kohana::$environment !== Kohana::PRODUCTION,
  'expose'     => Kohana::$environment !== Kohana::PRODUCTION,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
  // 'auth'       => KOSEVEN_MODULE_PATH.'auth',       // Basic authentication
  'auth'       => MODPATH.'auth',       // Basic authentication
  // 'cache'      => MODPATH.'cache',      // Caching with multiple backends
  // 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
  // 'database'   => KOSEVEN_MODULE_PATH.'database',   // Database access
  'database'   => MODPATH.'database',   // Database access
  // 'image'      => MODPATH.'image',      // Image manipulation
  // 'minion'     => KOSEVEN_MODULE_PATH.'minion',     // CLI Tasks
  'minion'     => MODPATH.'minion',     // CLI Tasks
  // 'orm'        => KOSEVEN_MODULE_PATH.'orm',        // Object Relationship Mapping
  'orm'        => MODPATH.'orm',        // Object Relationship Mapping
  // 'unittest'   => MODPATH.'unittest',   // Unit testing
  // 'userguide'  => KOSEVEN_MODULE_PATH.'userguide',  // User guide and API documentation
  'userguide'  => MODPATH.'userguide',  // User guide and API documentation
  'kostache'  => DOCROOT . 'vendor/zombor/kostache',
  // 'kostache'  => MODPATH.'zombor/kostache',
  ));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */

/**
 * @todo  Placer les routes dans un fichier séparé por aciliter la maintenance
 */

require_once APPPATH . 'routes' . EXT;


// API
// Route::set('api', '<controller>(/<action>)(.<format>)',
//   array(
//     'response_format' => '(json|html)',
//     'controller' => '(users|members)',
// //    'format' => '(json|xml)',
//   ))
//   ->defaults(array(
//       'controller' => 'Userguide',
//       'action' => 'index',
//     ));

// Shortcut URL for signin action
// Route::set('shortcut-signin', '<action>(/<id>)',
//   array(
//     'action'  => '(login|logout)',
//   ))
//   ->defaults(array(
//     'controller'  => 'App',
//     'action'    => 'login',
//   ));
// Subscription quick add
// Route::set('subscriptions_quickadd', 'members/subscriptions_quickadd/<member_id>/<subscription_id>')
//   ->defaults(array(
//     'controller'  => 'Members',
//     'action'      => 'subscriptions_quickadd',
//   ));
// Sections
// Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
//     array(
// //      'directory' => '(embers)',
//   ))
//   ->defaults(array(
//     'directory'   => 'Members',
//     'controller'  => 'Subscriptions',
//     'action'      => 'index',
//   ));
// Mustache js templates
// Route::set('templates', 'templates/<path>.mustache',
//   array(
//     'path'  => '[a-zA-Z0-9_/]+',
//   ))
//   ->defaults(array(
//       'controller'  => 'App',
//       'action'      => 'mustache_template',
//    //   'path'        => NULL,
//   ));

// Contrôleur d'actions
// Route::set('action', '<controller>/<action>')
//   ->defaults(array());
// Route::set('action_on_relation', '<controller>/<id>/<relation>/<relation_id>/<action>')
  // ->defaults(array());

// Exemple de capture de vue
// Route::set('MVVM_with_pretty_url_param', 'test/truc(/<param1>(/<param2>))')
//   ->defaults(array(
//     'controller' => 'MVVM',
//     'action'     => 'instanciate',
//     'view'        => 'test/truc',
//   ));


// /**
//  * Formulaire d'édition adhérent
//  */
// Route::set('member_edit', 'adherent/edit(/<id>)', array(
//     'id' => '\d+',
//   ))
//   ->defaults(array(
//     'controller'  => 'MVVM',
//     'action'      => 'instanciate',
//     'view'        => 'Members/Edit',
//   ));

// /**|
//  * Affichage d'un adhérent
//  */
// Route::set('member', 'adherent/<id>', array(
//     'id' => '\d+',
//   ))
//   ->defaults(array(
//     'controller'  => 'MVVM',
//     'action'      => 'instanciate',
//     'view'        => 'Members/Show',
//   ));

// /**
//  * Affichage des adhérents
//  *
//  * GET  /             : affiche tous les adhérents
//  * GET  /<id>         : affiche un adhérent
//  */
// Route::set('members', 'adherents(/<filter>)(/<folio>)', array(
//   'filter' => '(actifs|inactifs|tous)',
//   'folio' => '\d+',
//   ))
//   ->filter(function($route, $params, $request) {
//   })
//   ->defaults(array(
//     'controller'  => 'MVVM',
//     'action'      => 'instanciate',
//     'view'        => 'Members/Index',
//     'folio'       => 1,
//     'filter'      => 'tous'
//   ));

// /**
//  * Edition d'un bulletin
//  */
// Route::set('subscription_edit', 'bulletin/edit(/<id>)', array(
//   'id' => '\d+',
// ))
// ->defaults(array(
//   'controller' => 'MVVM',
//   'action' => 'instanciate',
//   'view' => 'Subscriptions/Edit',
// ));

// /**
//  * Affichage d'un bulletin
//  */
// Route::set('subscription', 'buttletin/<id>', array(
//   'id' => '\d+',
// ))
// ->defaults(array(
//   'controller' => 'MVVM',
//   'action' => 'instaniate',
//   'view' => 'Subscription/Show',
// ));

// /**
//  * Affichage des bulletins
//  */
// Route::set('subscriptions', 'bulletins(/<folio>)', array(
//   'folio' => '\d+',
// ))
// ->defaults(array(
//   'controller' => 'MVVM',
//   'action' => 'instanciate',
//   'view' => 'Subscriptions/Index',
// ));;


// /**
//  * Afichage d'un bulletin
//  */

// /**
//  * Affichage d'une adhésions
//  */
// Route::set('due', 'adhesion/<id>', array(
//   'id' => '\d+',
// ))
// ->defaults(array(
//   'controller' => 'MVVM',
//   'action' => 'instanciate',
//   'view'  => 'Dues/Show',
// ));

// /**
//  * Affiche des adhésions
//  */
// Route::set('dues', 'adhésions(/<folio>)', array(
//   'folio' =>'\d+',
// ))
// ->defaults(array(
//   'controller' => 'MVVM',
//   'action' => 'instanciate',
//   'view' => 'Dues/Index'
// ));


// /*
// Route::set('members_manage', 'adherents(/<id>)(/<action>)', array(
//     'id' => '\d+',
//     'action' => '(delete|autre)',
//   ))
//   ->filter(function($route, $params, $request) {

//     // Throw 404 si ce n'est pas le verbe HTTP POST
//     if ($request->method() !== HTTP_Request::POST)
//     {
//       return FALSE;
//     }

//     $id = Arr::get($params, 'id', NULL);
//     $member = ORM::factory('Member', $id)->values($request->post());

//     try
//     {
//       $member->save();
//     }
//     catch (ORM_Validation_Exception $e)
//     {
//       $errors = $e->errors();
//     }

//     // Construire la réponse (entête, status, body)
//     // retour
//     echo Debug::vars($params);
//     // Modifier une entrée
//     if ($is_post && isset($params['id']))
//     {
//       echo "Modifier {$param['id']}";
//       return;
//     }

//     // Ajouter une entrée
//     if ($is_post) {
//       echo "Ajouter une entrée";
//       return;
//     }

//     // Accomplir une action sur une entrée
//     if ($is_get && isset($params['id']) && isset($params['action']))
//     {
//       echo "Action {$params['action']} sur l'entrée {$params['id']}";
//       return;
//     }

//     // Afficher une entrée
//     if ($is_get && isset($params['id']))
//     {
//       echo "Afficher {$params['id']}";
//       return;
//     }

//     // Afficher toutes les entrée (default)
//     return false; // 404


//   })
//   ->defaults(array(
//     'controller'  => 'MVVM',
//     // 'action'      => 'instanciate',
//     'view'        => 'members' // Route::filter() pur changer la vue  (detected le verbe http?
//   ));
// */
// // Tente de capturer une vue par défaut
// // Un format de rendu peut être choisi
// // Une vue non trouvée, généreras une erreur HTTP 404
// Route::set('default', '(<view>)(.<format>)',
//   array(
//     // 'view' => '.*',
//     'view' => '^[^\.^?]*',
//     'format' => '(json|html)',
//   ))
//   ->filter(function($route, $params, $request) {
//     if ($params['format'] === 'json')
//     {
//       // /!\ Attention, il faut que le contrôleur MVVM (ou le modèle de vue?)
//       // modifie le `content-type` de la réponse HTTP.
//       $params['layout'] = 'layouts/json';
//     }

//     return $params;
//   })
//   ->defaults(array(
//     'controller' => 'MVVM',
//     'action'     => 'instanciate',
//     'view'       => 'Dashboard',
//     'layout'     => 'layouts/default',
//     'format'     => 'html',
//   ));

// Defaults
// Route::set('default', '(<controller>(/<action>(/<id>)))')
//   ->defaults(array(
//     // 'controller' => 'Dashboard',
//     // 'action'     => 'index',
//   ));

// Route::set('api', '<controller>(/<action>)(.<format>)',
//   array(
//     'response_format' => '(json|html)',
//     'controller' => '(users|members)',
// //    'format' => '(json|xml)',
//   ))
//   ->defaults(array(
//       'controller' => 'Userguide',
//       'action' => 'index',
//     ));
