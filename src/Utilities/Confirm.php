<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;
use EasyCSRF\Exceptions\InvalidCsrfTokenException as InvalidCsrfTokenException;

class Confirm extends BuildForm {

  private $formData;

  public function __construct() {
    parent::__construct();
    $this->formData = $_POST;
    
    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
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
      ];
      return $template->render($data);

    } catch(InvalidCsrfTokenException $e) {
      echo $e->getMessage();
    }

    
  }

  
}

