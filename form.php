<?php

$form_data = file_get_contents('./form_data.json');
$form_data_array = json_decode($form_data);

// var_dump($form_data_array);

function parse_tag($data) {

  // Create arguments
  $type = $data->type;
  $label = isset($data->label) ? "<label for=\"{$data->id}\">{$data->label}</label>" : "";
  $legend = isset($data->legend) ? "<legend>{$data->legend}</legend>" : "";
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

  // Templating inline text box
  if($type === "text" || $type === "email") {
    echo<<<EOF
    <div class="form-group">
      <div class="form-group-main">
        {$label}
        <input type="{$type}" $placeholder $value $name $id $minlength $maxlength $required $readonly />
      </div>
      {$assist_messages}
      {$error_messages}
    </div>\n
    EOF;
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

echo '<form action="./confirm.php" method="POST">';
var_dump($form_data_array);
foreach($form_data_array->formElements as $element) {
  parse_tag($element);
}
echo '<button type="submit">内容を確認</button>';
echo '</form>';