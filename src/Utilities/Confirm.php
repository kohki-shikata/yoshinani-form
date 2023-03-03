<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;

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
    $template = $this->twig->load('/page/confirm.html.twig');
    $data = [
      'data' => $this->formData,
      'state' => 'confirm',
      'form_settings' => $this->initial_settings,
    ];
    return $template->render($data);
  }

  
}

