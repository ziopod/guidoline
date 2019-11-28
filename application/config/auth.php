<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

	'driver'       => 'File',
	'hash_method'  => 'sha256',
	'hash_key'     => getEnv('HASH_KEY'),
	'lifetime'     => 1209600,
	'session_type' => Session::$default,
	'session_key'  => getEnv('SESSION_KEY'),

	// Username/password combinations for the Auth File driver
	'users' => array(
		getEnv('AUTH_ADMIN_USERNAME') => getEnv('AUTH_ADMIN_PASSWORD'),
	),

);
