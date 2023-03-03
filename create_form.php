<?php
// $data = $_POST;
$data = json_decode(file_get_contents('php://input'), true);

$env_data = [];
$env_data['SMTP_HOST'] = $data['initialSetting']['smtpHost'];
$env_data['SMTP_USERNAME'] = $data['initialSetting']['smtpUsername'];
$env_data['SMTP_PASSWORD'] = $data['initialSetting']['smtpPassword'];
$env_data['SMTP_PORT'] = $data['initialSetting']['smtpPort'];
$env_data['RECIPIENT_ADDRESS'] = $data['initialSetting']['recipientAddress'];
$env_data['RECIPIENT_NAME'] = $data['initialSetting']['recipientName'];

$env_text;

foreach($env_data as $key => $value) {
  global $env_text;
  $env_text .= "${key}=\"${value}\"\n";
}

file_put_contents('.env', $env_text);

unset($data['initialSetting']['smtpHost']);
unset($data['initialSetting']['smtpUsername']);
unset($data['initialSetting']['smtpPassword']);
unset($data['initialSetting']['smtpPort']);
unset($data['initialSetting']['recipientAddress']);
unset($data['initialSetting']['recipientName']);

$json = json_encode($data);

// echo '<pre>';
// echo $json->formElements;
// echo '</pre>';

file_put_contents('form_data.json', $json);