<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

class BuildForm {
  
  protected $initial_settings;
  protected $form_elements;
  private $loader;
  protected $twig;

  function __construct() {
    $form_data = file_get_contents(__DIR__ . '/../../form_data.json');
    $form_data_array = json_decode($form_data);
    $this->initial_settings = $form_data_array->initialSetting;
    $this->form_elements = $form_data_array->formElements;
    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../views');
    $this->twig = new \Twig\Environment($this->loader);
  }

  public function inline_text_box($data) {
    $type = $data->type;
    if($type === 'text' || $type === 'number' || $type === 'password' || $type === 'email' || $type === 'url') {
      $template = $this->twig->load('/partial/inline_text.html.twig');
      $data = [
        'type' => $type,
        'label' => $data->label,
        'name' => $data->name,
        'id' => $data->id,
        'value' => $data->value,
        'placeholder' => $data->placeholder,
        'required' => $data->required,
        'readonly' => $data->readonly,
        'disabled' => $data->disabled,
      ];
      return $template->render($data);
    }
  }

  public function check_radio($data) {
    $type = $data->type;
    if($type === 'checkbox' || $type === 'radio') {
      $template = $this->twig->load('/partial/check_radio.html.twig');
      $data = [
        'type' => $type,
        'title' => $data->title,
        'name' => $data->name,
        'id' => $data->id,
        'choices' => $data->choices,
      ];
      return $template->render($data);
    }
  }

  public function loop_out() {
    $form_data = null;
    foreach($this->form_elements as $element) {
      global $form_data;
      $form_data .= $this->inline_text_box($element);
      $form_data .= $this->check_radio($element);
    }
    return $form_data;
  }
}

// var_dump($form_data_array);

function parse_tag($data) {

  // Create arguments
  $type = $data->type;
  $label = isset($data->label) ? "<label for=\"{$data->id}\">{$data->label}</label>" : null;
  $legend = isset($data->legend) ? "<legend>{$data->legend}</legend>" : null;
  $name = isset($data->name) ? "name=\"{$data->name}\"" : "";
  $id = isset($data->id) ? "id=\"{$data->id}\"" : "";
  $value = isset($data->value) ? "value=\"{$data->value}\"" : "";
  $placeholder = isset($data->placeholder) ? "placeholder=\"{$data->placeholder}\"" : "";
  $minlength = isset($data->minlength) ? "minlength=\"{$data->minlength}\"" : "";
  $maxlength = isset($data->maxlength) ? "maxlength=\"{$data->maxlength}\"" : "";
  $required = isset($data->required) && $data->required === true ? "required" : "";
  $readonly = isset($data->readonly) && $data->readonly === true ? "readonly" : "";
  
  // Build assist messages
  $assist_messages = "";
  if(isset($data->assistMessages)) {
    $assist_inner_message = "";
    foreach($data->assistMessages as $m) { 
      $assist_inner_message .= "<li>{$m}</li>\n";
    }
    $assist_messages = <<<EOF
    <ul class="assist-messages">
      {$assist_inner_message}
    </ul>
    EOF;
  }

  // Build error messages
  $error_messages = "";
  if(isset($data->errorMessages)) {
    $error_inner_message = "";
    foreach($data->errorMessages as $m) { 
      $messageType = isset($m->type) ? $m->type : "";
      $messageText = isset($m->message) ? $m->message : "";
      $error_inner_message .= "<li data-error-type=\"{$messageType}\">{$messageText}</li>\n";
      // print_r($m);
    }
    $error_messages = <<<EOF
    <ul class="error-messages">
      {$error_inner_message}
    </ul>
    EOF;
  }

  // Build up checkbox and radio buttons
  if(isset($data->elements)) {
    $elements = "";
    foreach($data->elements as $e) {
      $e_id = isset($e->id) ? "id=\"{$e->id}\"" : "";
      $e_value = isset($e->value) ? "value=\"{$e->value}\"" : "";
      $e_label = isset($e->label) && isset($e->id) ? "<label for=\"{$e->id}\">{$e->label}</label>" : "";
      if($type === "radio") {
        $elements .= "<div class=\"element-wrap\">";
        $elements .= "<input type=\"radio\" {$e_id} {$name} {$e_value} />\n";
        $elements .= $e_label;
        $elements .= "</div>";
      };
      if($type === "checkbox") {
        $elements .= "<div class=\"element-wrap\">";
        $elements .= "<input type=\"checkbox\" {$e_id} {$name} {$e_value} />\n";
        $elements .= $e_label;
        $elements .= "</div>";
      };
    }
  }



  // Templateing checkboxes and radio buttons
  if($type === "radio" || $type === "checkbox") {
    echo<<<EOF
    <fieldset class="element-group">
      {$legend}
      {$elements}
    </fieldset>
    EOF;
  }
}

// echo '<form action="./confirm.php" method="POST">';
// var_dump($form_data_array);
// foreach($form_data_array->formElements as $element) {
//   parse_tag($element);
// }
// echo '<button type="submit">内容を確認</button>';
// echo '</form>';