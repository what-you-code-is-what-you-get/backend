<?php

namespace Drupal\Tests\challenge\Unit;

use PHPUnit\Framework\TestCase;
use Drupal\challenge\Controller\ChallengeController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\node\NodeStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Example test for ChallengeController.
 *
 * @group challenge
 */
class ChallengeControllerTest extends TestCase {

    public function testDeleteSubmissions() {
        // Mock the Request object
        $request = $this->createMock(Request::class);
      
        // Mock the NodeStorage object
        $node_storage = $this->createMock(NodeStorageInterface::class);
      
        // Mock the EntityTypeManager object
        /** @var EntityTypeManagerInterface|MockObject $entity_type_manager */
        $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
        $entity_type_manager->method('getStorage')
          ->willReturn($node_storage);
      
        // Mock the submissions and votes
        $submission = $this->createMock('Drupal\node\Entity\Node');
        $submission->method('id')
          ->willReturn(1);
        $vote = $this->createMock('Drupal\node\Entity\Node');
      
        // Mock the loadByProperties method
        $node_storage->method('loadByProperties')
          ->willReturnMap([
            [['type' => 'submission', 'field_challenge_id' => 1], [$submission]],
            [['type' => 'vote', 'field_challenge_id' => 1], [$vote]],
          ]);
      
        // Mock the delete method
        $submission->expects($this->once())
          ->method('delete');
        $vote->expects($this->once())
          ->method('delete');
      
        // Create the controller and pass the entity type manager
        $controller = new ChallengeController($entity_type_manager);
      
        // Call the deleteSubmissions method
        $response = $controller->deleteSubmissions($request, 1);
      
        // Assert the response
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Submissions deleted successfully.', $data['message']);
        $this->assertEquals(1, $data['deleted_count']);
        $this->assertTrue($data['success']);
    }

    /**
     * This is an example test for the add method, which is also just a test method.
     */
    public function testAdd() {
        // Create the controller without any dependencies
        /** @var EntityTypeManagerInterface|MockObject $entity_type_manager */
        $entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);
        $controller = new ChallengeController($entity_type_manager);

        // Call the add method
        $result = $controller->add(1, 2);

        // Assert the result
        $this->assertEquals(3, $result);
    }

}
