<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;
use EasyCSRF\Exceptions\InvalidCsrfTokenException as InvalidCsrfTokenException;
use Valitron\Validator as Validator;

class Confirm extends BuildForm {

  protected $formData;

  public function __construct() {
    parent::__construct();
    $this->formData = $_POST;
    $this->formData = $this->validate()[0];
    
    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
  }

  public function validate() {
    Validator::lang('ja');

    $val = new Validator($this->formData);

    // var_dump($this->formData);
    // echo '<pre>';
    // var_dump($this->form_elements);
    // echo '</pre>';

    foreach($this->form_elements as $element) {
      foreach($element as $key => $value) {
        if($key === "required" && $value === true) {
          // print_r([$key, $value]);
          $val->rule('required', $element->name)->message('{field} is required.');
        }
        if($key === "type" && $value === "email") {
          $val->rule('email', $element->name)->message('{field} value is not collect address pattern.');
        }
        if($key === "maxlength" && $value) {
          $val->rule('lengthMax', $element->name, $value)->message("Please write within {$value} characters.");
        }
        if($key === "minlength" && $value) {
          $val->rule('lengthMin', $element->name, $value)->message("Please write at least {$value} characters.");
        }
        if($key === "pattern" && $value) {
          $val->rule('regex', $element->name, "/{$value}/")->message("Invalid syntax.");;
        }
      }
    }
    if(!$val->validate()) {
      foreach($this->form_elements as $element) {
        foreach($element as $key => $value) {
          if($key === "name" && isset($val->errors()[$value])) {
            // var_dump($this->formData[$element->name]);
            $this->formData[$element->name] .= '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
          }
        }
      }
    }
    // echo '<pre>';
    // var_dump($this->formData);
    // echo '</pre>';

    $check_errors = [];
    foreach($this->formData as $key => $value) {
      $flag = preg_match('/"error-message"/', $value);
      array_push($check_errors, $flag);
    }

    // echo '<pre>';
    // var_dump($check_errors);
    // echo '</pre>';

    // print_r($val->validate());

    return [$this->formData, in_array(true, $check_errors) ? true : false ];
  }

  public function render() {
    try {
      session_start();
      $this->csrf->check('my_token', $this->formData['csrf_token']);
      unset($this->formData['csrf_token']);
      $template = $this->twig->load('/page/confirm.html.twig');
      $data = [
        'data' => $this->formData,
        'state' => 'confirm',
        'form_settings' => $this->initial_settings,
        'csrf_token' => $this->csrf->generate('new_token'),
        'has_error' => $this->validate()[1],
      ];

      $this->validate();

      return $template->render($data);

    } catch(InvalidCsrfTokenException $e) {
      echo $e->getMessage();
    }

    
  }

  
}

