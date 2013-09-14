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
		'Guidoline' => '2473a3f62500eec0b88220f5fd76423cfd47c93016abda4f701ad13d5e576329',
	),

);
