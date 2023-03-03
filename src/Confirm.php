<?php

namespace App;

require_once __DIR__ . '/../vendor/autoload.php';

class Confirm {

  private $formData;
  private $formSettings;
  private $form_settings;
  // private $loader;
  // private $twig;

  function __construct() {
    $this->formData = $_POST;
    $this->formSettings = file_get_contents('./form_data.json');
    $this->form_settings = json_decode($this->formSettings);

    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    
  }

  public function get_form_element($value) {
    $key = array_search($value, array_column($this->form_settings->formElements, 'name'));
    return $this->formData[$key];
  }

  public function render() {
    $template = $this->twig->load('/page/confirm.html.twig');
    $data = [
      'data' => $this->formData,
      'form_settings' => $this->form_settings,
    ];
    return $template->render($data);
  }

  
}

