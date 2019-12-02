<?php defined('SYSPATH') OR die('No direct script access.');

return array(
    'native' => array(
      'name' => getEnv('SESSION_NAME'),
      'lifetime' => 86400,
    )
);
