<?php
// Settings.local.php for Rancher.
// @codingStandardsIgnoreFile

$databases['default']['default'] = array(
  'database' => getenv('DRUPAL_DATABASE_NAME'),
  'username' => getenv('DRUPAL_DATABASE_USER'),
  'password' => getenv('DRUPAL_DATABASE_PASSWORD'),
  'prefix' => '',
  'host' => getenv('DRUPAL_DATABASE_HOST'),
  'port' => getenv('DRUPAL_DATABASE_PORT_NUMBER'),
  'namespace' => 'Drupal\Core\Database\Driver\mysql',
  'driver' => 'mysql',
);

$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT');

$settings['trusted_host_patterns'][] = '^dashboard\.wyciwyg\.dev$';

$config['system.performance']['css']['preprocess'] = TRUE;
$config['system.performance']['js']['preprocess'] = TRUE;

$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_addresses'] = array($_SERVER['REMOTE_ADDR']);

$config['rest_api_authentication.settings']['api_token'] = getenv('REST_API_TOKEN');

$settings['container_yamls'][] = DRUPAL_ROOT . '/sites/services.yml';

$config['site_checker_client.settings']['token'] = getenv('SITE_CHECKER_CLIENT_TOKEN');
