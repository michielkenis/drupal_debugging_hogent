<?php

$databases['default']['default'] = array (
  'database' => 'db_drupal',
  'username' => 'db_drupal',
  'password' => 'drupal',
  'prefix' => '',
  'host' => 'mariadb',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = 'ehKgsleQwi-lgOOi8SyIJ3ylno_qjazkydULiCmQnymQCVUvk0bP9irspHUnmzbGKLUmIVZ1hw';

// Config.
$config_directories['sync'] = '../config/sync';

// Enable local development services.
$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

ini_set('max_execution_time', 0);