<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';

use Valitron\Validator as Validator;

class BuildForm {
  
  protected $initial_settings;
  protected $form_elements;
  private $loader;
  protected $twig;
  protected $csrf;

  function __construct() {
    $form_data = file_get_contents(__DIR__ . '/../../form_data.json');
    $form_data_array = json_decode($form_data);
    $this->initial_settings = $form_data_array->initialSetting;
    $this->form_elements = $form_data_array->formElements;
    $this->loader = new \Twig\Loader\FilesystemLoader($this->views_path());
    $this->twig = new \Twig\Environment($this->loader, [
      'debug' => true,
    ]);
    $this->twig->addExtension(new \Twig\Extension\DebugExtension());
    $session_provider = new \EasyCSRF\NativeSessionProvider();
    $this->csrf = new \EasyCSRF\EasyCSRF($session_provider);
    $this->screen_setting = $form_data_array->screenSetting;
  }

  public function validate($val_data, $type = 'confirm') {
    $_SESSION = [];
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
      if($type === 'confirm') {
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
                $val_data[$element->name] = '';
                $val_data[$element->name] .= '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
              }
            }
          }
        }
      } else {
        foreach($this->form_elements as $element) {
          foreach($element as $key => $value) {
            if($key === "name" && isset($val->errors()[$value])) {
              if(gettype(isset($val_data[$element->name])) === 'array') {
                // $glued_array;
                // foreach($val_data[$element->name] as $item) {
                //   global $glued_array;
                //   $glued_array = implode(',', $item);
                // }
                // $val_data[$element->name] = $glued_array;
                $val_data[$element->name] = '';
                $val_data[$element->name] = '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
              } else {
                $val_data[$element->name] = '';
                $val_data[$element->name] = '<p class="error-message">' . $val->errors()[$value][0] . '</p>';
              }
            }
          }
        }

        $_SESSION['flash']['message'] = $val_data;

        header('Location: /');
        var_dump($val_data);
        exit;
        // var_dump($val->errors());
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

  public function output_csrf_token() {

    // session_start();
    $token = $this->csrf->generate('my_token');
    
    return $token;

  }

  public function views_path($theme_name = null) {
    $views_path = __DIR__ . '/../../views';
    if($theme_name !== null) {
      $users_path = $views_path . '/user/' . $theme_name;
      if(file_exists($users_path)) {
        return $users_path;
      } else {
        if($theme_name === 'plain' || $theme_name === 'bootstrap5') {
          $core_path = $views_path . '/core/' . $theme_name;
          return $core_path;
        } else {
          die('Invalid views name');
        }
      }
    }
      return $views_path . '/core/plain';
  }

  public function inline_text_box($data) {
    $type = isset($data->type) ? $data->type : $data['type'];
    if($type === 'text' || $type === 'number' || $type === 'password' || $type === 'email' || $type === 'url') {
      $template = $this->twig->load('/partial/inline_text.html.twig');
      $data = [
        'type' => $type,
        'label' => isset($data->label) ? $data->label : $data['label'],
        'name' => isset($data->name) ? $data->name : $data['name'],
        'id' => isset($data->id) ? $data->id : $data['id'],
        'size' => isset($data->size) ? $data->size : $data['size'],
        'inputmode' => isset($data->inputmode) ? $data->inputmode : $data['inputmode'],
        'value' => isset($data->value) ? $data->value : $data['value'],
        'placeholder' => isset($data->placeholder) ? $data->placeholder : $data['placeholder'],
        'pattern' => isset($data->pattern) ? $data->pattern : $data['pattern'],
        'required' => isset($data->required) ? $data->required : $data['required'],
        'readonly' => isset($data->readonly) ? $data->readonly : $data['readonly'],
        'disabled' => isset($data->disabled) ? $data->disabled : $data['disabled'],
      ];
      return $template->render($data);
    }
  }

  public function hidden($data) {
    $type = isset($data->type) ? $data->type : $data['type'];
    if($type === 'hidden') {
      $template = $this->twig->load('/partial/hidden.html.twig');
      $data = [
        'type' => $type,
        'name' => $data->name,
        'id' => $data->id,
        'value' => $data->value,
      ];
      return $template->render($data);
    }
  }

  public function check_radio($data) {
    $type = isset($data->type) ? $data->type : $data['type'];
    if($type === 'checkbox' || $type === 'radio') {
      $template = $this->twig->load('/partial/check_radio.html.twig');
      $data = [
        'type' => $type,
        'title' => isset($data->label) ? $data->label : $data['label'],
        'required' => isset($data->required) ? $data->required: $data['required'],
        'name' => isset($data->name) ? $data->name : $data['name'],
        'id' => isset($data->id) ? $data->id : $data['id'],
        'choices' => isset($data->choices) ? $data->choices : $data['choices'],
      ];
      return $template->render($data);
    }
  }

  public function select($data) {
    $type = isset($data->type) ? $data->type : $data['type'];
    if($type === 'select') {
      $template = $this->twig->load('/partial/select.html.twig');
      $data = [
        'type' => $type,
        'label' => isset($data->label) ? $data->label : $data['label'],
        'name' => isset($data->name) ? $data->name : $data['name'],
        'id' => isset($data->id) ? $data->id : $data['id'],
        'choices' => isset($data->choices) ? $data->choices : $data['choices'],
      ];
      return $template->render($data);
    }
  }

  public function textarea($data) {
    $type = isset($data->type) ? $data->type : $data['type'];
    if($type === 'textarea') {
      $template = $this->twig->load('/partial/textarea.html.twig');
      $data = [
        'type' => $type,
        'label' => isset($data->label) ? $data->label : $data['label'],
        'name' => isset($data->name) ? $data->name : $data['name'],
        'id' => isset($data->id) ? $data->id : $data['id'],
        'inputmode' => isset($data->inputmode) ? $data->inputmode : $data['inputmode'],
        'value' => isset($data->value) ? $data->value : $data['value'],
        'rows' => isset($data->rows) ? $data->rows : $data['rows'],
        'cols' => isset($data->cols) ? $data->cols : $data['cols'],
        'spellcheck' => isset($data->spellcheck) ? $data->spellcheck : $data['spellcheck'],
        'wrap' => isset($data->wrap) ? $data->wrap : $data['wrap'],
        'minlength' => isset($data->minlength) ? $data->minlength : $data['minlength'],
        'maxlength' => isset($data->maxlength) ? $data->maxlength : $data['maxlength'],
        'placeholder' => isset($data->placeholder) ? $data->placeholder : $data['placeholder'],
        'required' => isset($data->required) ? $data->required : $data['required'],
        'readonly' => isset($data->readonly) ? $data->readonly : $data['readonly'],
        'disabled' => isset($data->disabled) ? $data->disabled : $data['disabled'],
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
      $form_data .= $this->textarea($element);
      $form_data .= $this->select($element);
      $form_data .= $this->hidden($element);
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
  // $error_messages = "";
  // if(isset($data->errorMessages)) {
  //   $error_inner_message = "";
  //   foreach($data->errorMessages as $m) { 
  //     $messageType = isset($m->type) ? $m->type : "";
  //     $messageText = isset($m->message) ? $m->message : "";
  //     $error_inner_message .= "<li data-error-type=\"{$messageType}\">{$messageText}</li>\n";
  //     // print_r($m);
  //   }
  //   $error_messages = <<<EOF
  //   <ul class="error-messages">
  //     {$error_inner_message}
  //   </ul>
  //   EOF;
  // }

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