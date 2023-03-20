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
      // Check host whitelist
      $this->check_host();

      // Session stuff
      session_start();
      $this->csrf->check('my_token', $this->formData['csrf_token']);
      unset($this->formData['csrf_token']);

      // Initiate template
      $template = $this->twig->load('/page/confirm.html.twig');
      
      // Initiate labels if they exist
      $labels = $this->formData['label'];
      unset($this->formData['label']);

      // Input Array data to String
      $stringified_data = $this->array_to_string($this->formData);

      // var_dump($formData);

      $data = [
        'data' => $stringified_data,
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

