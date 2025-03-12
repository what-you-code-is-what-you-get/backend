<?php

namespace Drupal\Tests\challenge\Unit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

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

    protected Request|MockObject $request;
    protected NodeStorageInterface|MockObject $node_storage;
    protected EntityTypeManagerInterface|MockObject $entity_type_manager;
    protected ChallengeController $controller;

    /**
     * {@inheritdoc}
     * @return void
     */
    protected function setUp(): void {
        parent::setUp();

        $this->request = $this->createMock(Request::class);
        $this->node_storage = $this->createMock(NodeStorageInterface::class);
        $this->entity_type_manager = $this->createMock(EntityTypeManagerInterface::class);

        $this->entity_type_manager->method('getStorage')
            ->willReturn($this->node_storage);

        $this->controller = new ChallengeController($this->entity_type_manager);
    }

    #[Test]
    public function it_can_delete_submissions() {
        
        // Mock the submissions and votes
        $submission = $this->createMock('Drupal\node\Entity\Node');
        $submission->method('id')
          ->willReturn(1);
        $vote = $this->createMock('Drupal\node\Entity\Node');
      
        // Mock the loadByProperties method
        $this->node_storage->method('loadByProperties')
          ->willReturnMap([
            [['type' => 'submission', 'field_challenge_id' => 1], [$submission]],
            [['type' => 'vote', 'field_challenge_id' => 1], [$vote]],
          ]);
      
        // Mock the delete method and expect it to be called once
        $submission->expects($this->once())
          ->method('delete');
        $vote->expects($this->once())
          ->method('delete');
      
        // Create the controller and pass the entity type manager
        $controller = new ChallengeController($this->entity_type_manager);
      
        // Call the deleteSubmissions method
        $response = $controller->deleteSubmissions($this->request, 1);
      
        // Assert the response
        $this->assertJsonResponse($response, 'Submissions deleted successfully.');
    }

    #[Test]
    function it_can_delete_votes()
    {
        
        // Mock the submissions and votes
        $submission = $this->createMock('Drupal\node\Entity\Node');
        $submission->method('id')
          ->willReturn(1);
        $vote = $this->createMock('Drupal\node\Entity\Node');
      
        // Mock the loadByProperties method
        $this->node_storage->method('loadByProperties')
          ->willReturnMap([
            [['type' => 'submission', 'field_challenge_id' => 1], [$submission]],
            [['type' => 'vote', 'field_challenge_id' => 1], [$vote]],
          ]);
      
        // Mock the delete method and expect it to be called once
        $vote->expects($this->once())
          ->method('delete');
      
        // Create the controller and pass the entity type manager
        $controller = new ChallengeController($this->entity_type_manager);
      
        // Call the deleteSubmissions method
        $response = $controller->deleteVotes($this->request, 1);
      
        // Assert the response
        $this->assertJsonResponse($response, 'Votes deleted successfully.');
    }

    /**
     * Asserts that the response is a JSON response with the expected message.
     * @param JsonResponse $response
     * @param string $expectedMessage
     * @return void
     */
    private function assertJsonResponse(JsonResponse $response, string $expectedMessage): void {
        $this->assertInstanceOf(JsonResponse::class, $response);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals($expectedMessage, $data['message']);
        $this->assertSame(1, $data['deleted_count']);
        $this->assertTrue($data['success']);
    }    

}
