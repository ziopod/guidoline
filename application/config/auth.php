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
		'Guidoline' => 'fea0bfb2b0414c22853894d5f6c879f63757e0953a446a61e8eaa6e456780f8a',
		'Bertrand Keller'	=> '0f6a9d5ffa46bd6c99fd1dc3addc44cf2943d5afc6db7dfb0db465ae8b5df3b4',
	),

);
