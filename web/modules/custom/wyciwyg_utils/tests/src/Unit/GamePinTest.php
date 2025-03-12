<?php

namespace Drupal\Tests\wyciwyg_utils\Unit;

use Drupal\wyciwyg_utils\GamePinService;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\Query\QueryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests the GamePinService.
 *
 * @group wyciwyg_utils
 */
class GamePinTest extends TestCase {

  public function testGenerateUniqueGamePin() {
    // Mock the entity query to return an empty result.
    $queryMock = $this->createMock(QueryInterface::class);
    $queryMock->method('condition')->willReturnSelf();
    $queryMock->method('accessCheck')->willReturnSelf();
    $queryMock->method('range')->willReturnSelf();
    $queryMock->method('execute')->willReturn([]); // Simulate no existing PINs

    // Mock the entity storage to return the query mock.
    $storageMock = $this->createMock(EntityStorageInterface::class);
    $storageMock->method('getQuery')->willReturn($queryMock);

    // Mock the entity type manager to return the storage mock.
    $entityTypeManagerMock = $this->createMock(EntityTypeManagerInterface::class);
    $entityTypeManagerMock->method('getStorage')->willReturn($storageMock);

    // Create an instance of the service with the mocked dependencies.
    $gamePinService = new GamePinService($entityTypeManagerMock);

    // Call the method.
    $pin = $gamePinService->generateUniqueGamePin();

    // Assert the PIN is a 6-character alphanumeric string.
    $this->assertMatchesRegularExpression('/^[A-Z0-9]{6}$/', $pin);
  }
}
