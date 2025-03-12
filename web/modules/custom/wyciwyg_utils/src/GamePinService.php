<?php

namespace Drupal\wyciwyg_utils;

use Drupal\Core\Entity\EntityTypeManagerInterface;

class GamePinService {
  protected $entityTypeManager;

  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * Generate a unique 6-character game PIN.
   */
  public function generateUniqueGamePin() {
    do {
      $pin = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
    } while ($this->gamePinExists($pin));

    return $pin;
  }

  /**
   * Check if a game PIN already exists.
   */
  public function gamePinExists($pin, $exclude_nid = NULL) {
    $query = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'challenge')
      ->condition('field_game_pin', $pin)
      ->accessCheck(FALSE)
      ->range(0, 1);

    if ($exclude_nid) {
      $query->condition('nid', $exclude_nid, '!=');
    }

    return (bool) $query->execute();
  }
}
