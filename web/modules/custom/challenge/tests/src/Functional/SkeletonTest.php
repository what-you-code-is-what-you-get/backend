<?php

namespace Drupal\Tests\challenge\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Tests challenge node creation.
 *
 * @group challenge
 */
class SkeletonTest extends BrowserTestBase {

    /**
     * {@inheritdoc}
     */
    protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['node', 'user'];

  /**
   * A user with permission to create challenge nodes.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $authenticatedUser;

  protected function setUp(): void {
    parent::setUp();

    // Install the challenge module if it's not installed
    if (!\Drupal::moduleHandler()->moduleExists('challenge')) {
        $this->container->get('module_installer')->install(['challenge']);
    }

    // Verify the module is installed
    $this->assertTrue(\Drupal::moduleHandler()->moduleExists('challenge'), 'Challenge module is enabled.');

    // Ensure the content type exists (only if it's not already created).
    if (!$this->contentTypeExists('challenge')) {
        $this->drupalCreateContentType(['type' => 'challenge', 'name' => 'Challenge']);
    }

    // Create a user with the correct permissions
    $this->authenticatedUser = $this->drupalCreateUser([
        'bypass node access', // Adjust permissions as needed
    ]);

    // Log in as the test user
    $this->drupalLogin($this->authenticatedUser);
}

protected function contentTypeExists($type) {
    $types = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
    return isset($types[$type]);
}




  /**
   * Tests visiting the challenge node creation page and submitting the form.
   */
  public function testChallengeNodeCreation(): void {

    $fields = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', 'challenge');
    foreach ($fields as $field_name => $field_definition) {
        dump($field_name);
    }

    //dump(\Drupal::moduleHandler()->getModuleList());

    // Visit the challenge node creation page.
    $response = $this->drupalGet('node/add/challenge');
    //dump($this->getSession()->getPage()->getContent()); 
    //dump($response);

    // Assert that the form is present.
    $this->assertSession()->statusCodeEquals(200);
    $this->assertSession()->fieldExists('Title');

    $this->assertSession()->fieldExists('Game pin');

    // Fill the form fields and submit.
    $edit = [
      'title[0][value]' => 'Test Challenge',
      'body[0][value]' => 'This is a test challenge description.',
    ];
    $this->submitForm($edit, 'Save');

    // Check if the node was created.
    $this->assertSession()->pageTextContains('Test Challenge');
    $this->assertSession()->pageTextContains('has been created');
  }

}
