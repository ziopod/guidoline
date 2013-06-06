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
		// 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
		'demo' => '0db4b39f47594cbc24c35826107a2c45fd860aa58e7c37b12ba3f88589c94c7d',
	//	'demo' => '482e2a696458f71271413e07c5434b1cb8bb3bc3d243c922075251227aac494e',
	),

);
