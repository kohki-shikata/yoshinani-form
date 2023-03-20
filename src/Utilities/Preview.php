<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Utilities\BuildForm as BuildForm;

class Preview extends BuildForm {

  private $formData;

  public function __construct() {
    parent::__construct();
  }

  public function preview_loop_out($elements) {
    $form_data = null;
    foreach($elements as $element) {
      global $form_data;
      $form_data .= $this->inline_text_box($element);
      $form_data .= $this->check_radio($element);
      $form_data .= $this->textarea($element);
      $form_data .= $this->select($element);
      $form_data .= $this->hidden($element);
    }
    return $form_data;
  }

  public function render() {

    // Check whitelist of hosts
    $this->check_host();
    
    try {
      $postData = json_decode(file_get_contents("php://input"), true, 512, JSON_THROW_ON_ERROR);
    } catch(JSONException $e) {
      header("HTTP/1.1 400 Bad Request");
      header("Content-Type: application/json; charset=utf-8");
      $result = [
        'code' => 400,
        'message' => 'Invalid input',
      ];
      echo json_encode($result, JSON_UNESCAPED_UNICODE);
      exit;
    }

    $template = $this->twig->load('/page/preview_input.html.twig');
    $data = [
      'data' => $postData['formData'],
      'state' => 'input',
      // 'csrf_token' => $this->output_csrf_token(),
      'form_settings' => $postData['formData']['initialSetting'],
      'screen_setting' =>  $this->screen_setting,
      'form' => $this->preview_loop_out($postData['formData']['formElements']),
      // 'form' => $this->loop_out(),
    ];

    header("HTTP/1.1 200 OK");
    header("Content-Type: text/html; charset=utf-8");
    // var_dump($this->preview_loop_out($postData['formData']['formElements']));
    // var_dump($this->screen_setting);
    return $template->render($data);
  }

}

