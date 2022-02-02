<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * Configuration pour le service API Mailjet
 */
return array(
  'useragent' => 'Guidoline Members',
  'url' => 'https://api.mailjet.com/v3/REST/',
  'api_key' => getenv('MAILJET_KEY'),
  'api_secret' => getenv('MAILJET_SECRET'),
  'list_id' => getenv('MAILJET_LIST_ID')
);
