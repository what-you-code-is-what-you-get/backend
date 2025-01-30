<?php
namespace Drupal\challenge\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Class ChallengeController.
 *
 * Provides route responses for the Challenge module.
 */
class ChallengeController extends ControllerBase
{
  /**
   * Displays the lobby page for a challenge node.
   *
   * Loads a node by its ID, retrieves specific fields, and returns a render array 
   * to display the lobby page using a Twig template.
   *
   * @param NodeInterface $node
   *   The node entity.
   *
   * @return array
   *   A render array for the lobby page.
   */
  public function lobby(NodeInterface $node): array
  {
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
   * Displays the voting page for a challenge node.
   *
   * Loads a node by its ID, retrieves specific fields, and returns a render array 
   * to display the voting page using a Twig template.
   *
   * @param NodeInterface $node
   *   The node entity.
   *
   * @return array
   *   A render array for the voting page.
   */
  public function voting(NodeInterface $node): array
  {
    // Initialize variables
    $label = '';
    $game_pin = '';

    // Check if the node exists
    if ($node) {
      // Get the node label
      $label = $node->label();

      // Check if the node has the field_game_pin field
      if ($node->hasField('field_game_pin')) {
        // Get the value of the field_game_pin field
        $game_pin = $node->get('field_game_pin')->value;
      }
    }

    // Return the value in a render array using a Twig template
    return [
      '#theme' => 'challenge_voting_page',
      '#title' => $this->t('Voting - Challenge'),
      '#label' => $label,
      '#game_pin' => $game_pin,
      '#qr_code_uri' => $this->qrCode($node->id()),
      '#attached' => [
        'library' => [
          'challenge/challenge',
        ],
      ],
    ];
  }

  /**
   * Retrieves and renders submissions for a given challenge node.
   *
   * This function takes a challenge node as input, retrieves all submission nodes
   * associated with the challenge, and returns a render array to display the submissions
   * using a Twig template.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The challenge node for which submissions are to be retrieved.
   *
   * @return array
   *   A render array containing the challenge submissions to be displayed.
   */
  public function submissions(NodeInterface $node): array
  {
    // Initialize variables
    $challenge_id = $node->id();
    $label = '';
    $submissions = [];

    // Check if the node exists
    if ($node) {
      // Get the node label
      $label = $node->label();

      // Load all submission nodes with the same challenge ID
      $query = \Drupal::entityQuery('node')
        ->condition('type', 'submission')
        ->condition('field_challenge_id', $challenge_id)
        ->accessCheck(TRUE); // Explicitly set access check
      $nids = $query->execute();

      if (!empty($nids)) {
        $submissions = \Drupal\node\Entity\Node::loadMultiple($nids);
      }
    }

    // Return the value in a render array using a Twig template
    return [
      '#theme' => 'challenge_submissions_page',
      '#title' => $this->t('Submissions - Challenge'),
      '#label' => $label,
      '#challenge_id' => $challenge_id,
      '#submissions' => $submissions,
      '#attached' => [
        'library' => [
          'challenge/challenge',
        ],
      ],
    ];
  }

  /**
   * Retrieves and processes votes for a given challenge node.
   *
   * This function loads all vote nodes associated with the specified challenge node,
   * retrieves the corresponding submission nodes, and extracts the 'field_name' and
   * 'field_submission_id' values. It then counts the votes for each submission and
   * sorts the results by the highest count number.
   *
   * @param \Drupal\node\NodeInterface $node
   *   The challenge node.
   *
   * @return array
   *   A render array containing the voting results to be displayed, where each result
   *   is an associative array containing:
   *   - 'field_name': The name field from the submission node.
   *   - 'submission_id': The submission ID from the vote node.
   *   - 'count': The number of votes for the submission.
   */
  public function votes(NodeInterface $node): array
  {
    // Initialize variables
    $label = $node->label();
    $challenge_id = $node->id();
    $votes = $this->getAllVotes($challenge_id);
    $voting_results = [];

    if (!empty($votes)) {
      $voting_results = [];

      foreach ($votes as $vote) {
        $submission_id = $vote['field_submission_id'];
        if (!isset($voting_results[$submission_id])) {
          $voting_results[$submission_id] = [
            'field_name' => $vote['field_name'],
            'submission_id' => $submission_id,
            'count' => 0,
          ];
        }
        $voting_results[$submission_id]['count']++;
      }

      // Sort the voting results by the highest count number
      usort($voting_results, function ($a, $b) {
        return $b['count'] - $a['count'];
      });
    }

    // Return the value in a render array using a Twig template
    return [
      '#theme' => 'challenge_votes_page',
      '#title' => $this->t('Votes - Challenge'),
      '#label' => $label,
      '#voting_results' => $voting_results,
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

  /**
   * Generates a QR code for the voting URL of a challenge node.
   *
   * This method constructs a QR code for the voting URL of a challenge node
   * using the node ID and returns the QR code as a data URI.
   *
   * @param int $node_id
   *   The ID of the node.
   *
   * @return string
   *   The QR code as a data URI.
   */
  private function qrCode(int $node_id): string
  {
    $url = "https://wyciwyg.dev/vote/" . $node_id;

    $builder = new Builder(
      writer: new PngWriter(),
      writerOptions: [],
      validateResult: false,
      data: $url,
      encoding: new Encoding('UTF-8'),
      errorCorrectionLevel: ErrorCorrectionLevel::High,
      size: 600,
      margin: 10,
      roundBlockSizeMode: RoundBlockSizeMode::Margin,
    );

    $qr_qode = $builder->build();

    return $qr_qode->getDataUri();
  }

  /**
   * Retrieves all votes for a given challenge ID.
   *
   * This function loads all vote nodes associated with the specified challenge ID,
   * retrieves the corresponding submission nodes, and extracts the 'field_name' and
   * 'field_submission_id' values. It returns an array of votes with these details.
   *
   * @param int $challenge_id
   *   The ID of the challenge node.
   *
   * @return array
   *   An array of votes, where each vote is an associative array containing:
   *   - 'field_name': The name field from the submission node.
   *   - 'field_submission_id': The submission ID from the vote node.
   */
  protected function getAllVotes($challenge_id)
  {
    // Load all vote nodes with the same challenge ID
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'vote')
      ->condition('field_challenge_id', $challenge_id)
      ->accessCheck(TRUE); // Explicitly set access check
    $nids = $query->execute();

    $votes = [];
    if (!empty($nids)) {
      $vote_nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

      foreach ($vote_nodes as $vote_node) {
        $submission_id = $vote_node->get('field_submission_id')->value;
        $vote = [];
        if (!empty($nids)) {
          $submission_node = \Drupal\node\Entity\Node::load($submission_id);
          if ($submission_node) {
            $vote = [
              'field_name' => $submission_node->get('field_name')->value,
              'field_submission_id' => $vote_node->get('field_submission_id')->value,
            ];
          }
        }

        $votes[] = $vote;
      }
    }

    return $votes;
  }
}