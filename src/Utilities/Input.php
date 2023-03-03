<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;

class Input extends BuildForm {

  private $formData;

  public function __construct() {
    $this->formData = $_POST;
    parent::__construct();
  }

  public function render() {
    $template = $this->twig->load('/page/input.html.twig');
    $data = [
      'data' => $this->formData,
      'form_settings' => $this->initial_settings,
      'form' => $this->loop_out(),
    ];

    return $template->render($data);
  }

}

