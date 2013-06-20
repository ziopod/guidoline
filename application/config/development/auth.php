<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(

	'driver'       => 'File',
	'hash_method'  => 'sha256',
	'hash_key'     => 'R0mWYLFrfAF0PRBry9Ke4sWczco5HTmyLOdhhnae8dlZW3ONO2FpZvJU59l7sYDa',
	'lifetime'     => 1209600,
	'session_type' => Session::$default,
	'session_key'  => 'auth_user',

	// Username/password combinations for the Auth File driver
	'users' => array(
		'demo' => '482e2a696458f71271413e07c5434b1cb8bb3bc3d243c922075251227aac494e',
		// 'admin' => 'b3154acf3a344170077d11bdb5fff31532f679a1919e716a02',
	),

);
