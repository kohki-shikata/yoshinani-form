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
    $this->formData = $this->validate($this->formData);
    
    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
  }

  public function validate($val_data) {
    Validator::lang('ja');

    $val = new Validator($val_data);

    foreach($this->form_elements as $element) {
      foreach($element as $key => $value) {
        if($key === "required" && $value === true) {
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
          $val->rule('regex', $element->name, "/" . $value . "/")->message("Invalid syntax.");
        }
      }
    }
    if(!$val->validate()) {
      // var_dump($val_data);
      foreach($this->form_elements as $element) {
        foreach($element as $key => $value) {
          if($key === "name" && isset($val->errors()[$value])) {
            if(gettype(isset($val_data[$element->name])) === 'array') {
              $glued_array;
              foreach($val_data[$element->name] as $item) {
                global $glued_array;
                $glued_array = implode(',', $item);
              }
              $val_data[$element->name] = $glued_array;
              $val_data[$element->name] .= '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
            } else {
              $val_data[$element->name] .= '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
            }
        }
      }
    }
  }

    return $val_data;
  }

  public function error_flag() {
    $check_errors = [];
    foreach($this->formData as $key => $value) {
      if(gettype($value) === 'string') {
        $flag = preg_match('/"error-message"/', $value);
        array_push($check_errors, $flag);
      }
    }
    return in_array(true, $check_errors) ? true : false;
  }

  public function label_override() {
    foreach($this->formData as $key => $value) {
      
    }
  }

  public function render() {
    try {
      session_start();
      $this->csrf->check('my_token', $this->formData['csrf_token']);
      unset($this->formData['csrf_token']);
      $template = $this->twig->load('/page/confirm.html.twig');
      $labels = $this->formData['label'];
      unset($this->formData['label']);

      // Multiple select value array to string.
      $formData = array();
      foreach($this->formData as $key => $value) {
        global $formData;
        if(gettype($value) === 'array') {
          $value = implode(',', $value);
        }
        if(empty($value)) {
          $value = '(Not input)';
        }
        $formData[$key] = $value;
      }

      // var_dump($formData);

      $data = [
        'data' => $formData,
        'state' => 'confirm',
        'labels' => isset($labels) ? $labels : null,
        'form_settings' => $this->initial_settings,
        'csrf_token' => $this->csrf->generate('new_token'),
        'has_error' => $this->error_flag(),
      ];

      return $template->render($data);

    } catch(InvalidCsrfTokenException $e) {
      echo $e->getMessage();
    }

    
  }

  
}

