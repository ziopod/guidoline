<?php

return array(
  'server'    => getenv('SMTP_SERVER'),
  'port'      => 465,
  'ssl'       => TRUE,
  'username'  => getenv('SMTP_USERNAME'),
  'password'  => getenv('SMTP_PASSWORD'),
  'robot'     => array(
    'email' => getenv('SMTP_BOT_EMAIl'),
    'name'  => getenv('SMTP_BOT_NAME'),
  )
);
