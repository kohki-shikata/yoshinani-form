<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;
use EasyCSRF\Exceptions\InvalidCsrfTokenException as InvalidCsrfTokenException;

class Confirm extends BuildForm {

  protected $formData;

  public function __construct() {
    parent::__construct();
    $this->formData = $_POST;
    $this->formData = $this->validate($this->formData);
    
    $this->loader = new \Twig\Loader\FilesystemLoader($this->views_path());
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
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
        'screen_setting' => $this->screen_setting,
        'csrf_token' => $this->csrf->generate('new_token'),
        'has_error' => $this->error_flag(),
      ];

      return $template->render($data);

    } catch(InvalidCsrfTokenException $e) {
      echo $e->getMessage();
    }

    
  }

  
}

