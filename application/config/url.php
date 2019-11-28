<?php defined('SYSPATH') OR die('No direct script access.');

return array(
  /**
   * Setup Trusted Host in dotenv
   *
   * ~~~
   * TRUSTED_HOSTS='example.org,.*.example.org'
   * ~~~
   */
  'trusted_hosts' => explode(',',getenv('TRUSTED_HOSTS')),
);
