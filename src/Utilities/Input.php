<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;

class Input extends BuildForm {

  private $formData;

  public function __construct() {
    $this->formData = $_POST;
    parent::__construct();
    @session_start();
    $_SESSION = [];
    $this->twig->addGlobal('session', $_SESSION);
  }

  public function output_action() {
    $setting = $this->screen_setting;
    if($setting === 'input') {
      return '';
    } elseif($setting === 'confirm') {
      return '/confirm';
    } elseif($setting === 'complete') {
      return '/send';
    }
  }

  public function output_button_label() {
    $setting = $this->screen_setting;
    if($setting === 'input') {
      return 'Send';
    } elseif($setting === 'confirm') {
      return 'Confirm';
    } elseif($setting === 'complete') {
      return 'Send';
    }
  }

  public function render() {
    $template = $this->twig->load('/page/input.html.twig');
    $data = [
      'data' => $this->formData,
      'state' => 'input',
      'csrf_token' => $this->output_csrf_token(),
      'form_settings' => $this->initial_settings,
      'form' => $this->loop_out(),
      'screen_setting' => $this->screen_setting,
      'action' => $this->output_action(),
      'button_label' => $this->output_button_label(),
    ];

    return $template->render($data);
  }

}

