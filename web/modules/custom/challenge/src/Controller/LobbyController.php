<?php
namespace Drupal\challenge\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;

class LobbyController extends ControllerBase
{
  public function view(int $node_id): array
  {
    // Load the node by ID
    $node = Node::load($node_id);

    // Initialize variables
    $label = '';
    $game_pin = '';
    $time = '';

    // Check if the node exists
    if ($node) {
      // Get the node label
      $label = $node->label();

      // Check if the node has the field_game_pin field
      if ($node->hasField('field_game_pin')) {
        // Get the value of the field_game_pin field
        $game_pin = $node->get('field_game_pin')->value;
      }

      // Check if the node has the field_time field
      if ($node->hasField('field_time')) {
        // Get the value of the field_time field
        $time = $this->formatTime($node->get('field_time')->value);
      }
    }

    // Return the value in a render array using a Twig template
    return [
      '#theme' => 'challenge_lobby_page',
      '#title' => $this->t('Lobby - Challenge'),
      '#label' => $label,
      '#game_pin' => $game_pin,
      '#time' => $time,
      '#attached' => [
        'library' => [
          'challenge/challenge',
        ],
      ],
    ];
  }

  /**
   * Formats time from minutes to MM:SS.
   *
   * @param int $minutes
   *   The time in minutes.
   *
   * @return string
   *   The formatted time in MM:SS.
   */
  private function formatTime(int $minutes): string
  {
    $seconds = $minutes * 60;
    $formatted_minutes = floor($seconds / 60);
    $remaining_seconds = $seconds % 60;
    return sprintf('%02d:%02d', $formatted_minutes, $remaining_seconds);
  }
}