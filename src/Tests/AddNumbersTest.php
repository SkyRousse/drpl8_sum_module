<?php

namespace Drupal\add_numbers\Tests;
use Drupal\simpletest\WebTestBase;

/**
 * Ensure that the add_numbers form works properly.
 *
* @group add_numbers
 */
class AddNumbersTest extends WebTestBase {

  /**
   * Our module dependencies.
   *
   * @var array List of test dependencies.
   */
  static public $modules = array('add_numbers');

  /**
   * Test the form.
   */
  public function testAddNumbersForm() {

    // Verify that anonymous can access the page.
    $this->drupalGet('add-numbers');
    $this->assertResponse(200);

    // Post the form.
    $this->drupalPostForm(NULL, array(
        'number1' => 3,
        'number2' => 3,
      ), t('Add'));
      $this->assertText('The sum of the two numbers is 6');
  }

}
