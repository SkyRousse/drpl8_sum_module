<?php

/**
 * @file
 * Contains \Drupal\add_numbers\Form\AddNumbers
 */
namespace Drupal\add_numbers\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * Implements the add numbers form controller.
 */
class AddNumbers extends FormBase {

  /**
   * Build the simple form.
   *
   * A build form method constructs an array that defines how markup and
   * other form elements are included in an HTML form.
   *
   * @param array $form
   *   Default form array structure.
   * @param FormStateInterface $form_state
   *   Object containing current form state.
   *
   * @return array
   *   The render array defining the elements of the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['number1'] = [
      '#type' => 'textfield',
      '#title' => $this->t('1st Number'),
      '#description' => $this->t('Enter an initial number.'),
      '#required' => TRUE,
      '#ajax'=> [
        'callback' => '::sumCallback',
        'event' => 'keyup',
        'wrapper' => 'total-wrapper',
      ],
    ];

    $form['number2'] = [
      '#type' => 'textfield',
      '#title' => $this->t('2nd Number'),
      '#description' => $this->t('Enter a number to be added to the initial number.'),
      '#required' => TRUE,
      '#ajax'=> [
        'callback' => '::sumCallback',
        'event' => 'keyup',
        'wrapper' => 'total-wrapper',
      ],
      '#suffix' => '<span class="number-valid-message"></span>'
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    ];

    $form['total_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'total-wrapper'],
    ];

    return $form;
  }

  /**
   * Getter method for Form ID.
   */
  public function getFormId() {
    return 'add_numbers';
  }

  /**
 * Ajax callback to add number inputs and check that inputs are correct type and then display the sum of numbers or a message indicating input type needs to be numeric.
 */
  public function sumCallback(array &$form, FormStateInterface $form_state) {
    $firstNumber = $form_state->getValue('number1');
    $secondNumber = $form_state->getValue('number2');
    $total = $firstNumber + $secondNumber;
    $response = new AjaxResponse();
    if (!is_numeric($form_state->getValue('number1')) or !is_numeric($form_state->getValue('number2'))) {
      $response->addCommand(new HTMLCommand(
        '#total-wrapper',
        t('inputs should be numbers')));
    } else {
      $response->addCommand(new HTMLCommand(
        '#total-wrapper',
        t('sum = ') . $total));
    }
    return $response;
  }

  /**
   * Implements form validation.
   *
   * The validateForm method is the default method called to validate input on
   * a form.
   *
   * @param array $form
   *   The render array of the currently built form.
   * @param FormStateInterface $form_state
   *   Object describing the current state of the form.
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue('number1'))) {
      $form_state->setErrorByName('number1', $this->t('Whoops, first input needs to be a number'));
    }
    if (!is_numeric($form_state->getValue('number2'))) {
      $form_state->setErrorByName('number2', $this->t('Whoops, second input needs to be a number'));
    }
  }

  /**
   * Implements a form submit handler.
   *
   * The submitForm method is the default method called for any submit elements.
   *
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $sum = $form_state->getValue('number1') + $form_state->getValue('number2');
    drupal_set_message(t('The sum of the two numbers is %sum', ['%sum' => $sum]));
  }

}
