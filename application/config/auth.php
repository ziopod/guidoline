<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

	'driver'       => 'File',
	'hash_method'  => 'sha256',
	'hash_key'     => 'STt0zlkl6433p9MCy57ZTRmx2zDr0JWO3m5NwiiiMvLIZ82qJi61BSPvhS2ct03H',
	'lifetime'     => 1209600,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',

	// Username/password combinations for the Auth File driver
	'users' => array(
		'Guidoline' => '231934c19c11680bac20c9346092c0a4278093cf1f3bebf09ff5ea233fef2027',
	),

);
