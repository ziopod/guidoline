<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'native' => array(
      'name' => getEnv('SESSION_NAME'),
      'lifetime' => 86400,
    ),
    'cookie' => array(
      'name' => getEnv('COOKIE_NAME'),
      'encrypted' => TRUE,
      'salt' => getEnv('COOKIE_SALT'),
      'lifetime' => 86400,
    )
);
