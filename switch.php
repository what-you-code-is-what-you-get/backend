<?php

$profile_to_install = 'minimal';
  $profile_to_remove = \Drupal::installProfile();

  // Forces ExtensionDiscovery to rerun for profiles.
  \Drupal::state()->delete('system.profile.files');

  $configFactory = \Drupal::configFactory();
  $keyvalue = \Drupal::service('keyvalue');

  // Set the profile in configuration.
  $extension_config = $configFactory->getEditable('core.extension');
  $extension_config->set('profile', $profile_to_install)
    ->save();

  drupal_flush_all_caches();

  // Install profiles are also registered as enabled modules.
  // Remove the old profile and add in the new one.
  $extension_config->clear("module.{$profile_to_remove}")
    ->save();
  // The install profile is always given a weight of 1000 by the core
  // extension system.
  $extension_config->set("module.$profile_to_install", 1000)
    ->save();

  // Remove the schema value for the old install profile, and set the schema
  // for the new one. We set the schema version to 8000, in the absence of any
  // knowledge about it. TODO: add an option for the schema version to set for
  // the new profile, or better yet, analyse the profile's hook_update_N()
  // functions to deduce the schema to set.
  $keyvalue->get('system.schema')->delete($profile_to_remove);
  $keyvalue->get('system.schema')->set($profile_to_install, 8000);

  // Clear caches again.
  drupal_flush_all_caches();