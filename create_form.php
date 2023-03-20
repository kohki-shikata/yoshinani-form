<?php
// $data = $_POST;
try {
  $data = json_decode(file_get_contents('php://input'), true);

  $env_data = [];
  $env_data['SMTP_HOST'] = $data['initialSetting']['smtpHost'];
  $env_data['SMTP_USERNAME'] = $data['initialSetting']['smtpUsername'];
  $env_data['SMTP_PASSWORD'] = $data['initialSetting']['smtpPassword'];
  $env_data['SMTP_PORT'] = $data['initialSetting']['smtpPort'];
  $env_data['RECIPIENT_ADDRESS'] = $data['initialSetting']['recipientAddress'];
  $env_data['RECIPIENT_NAME'] = $data['initialSetting']['recipientName'];

  // ob_start();
  // var_dump($data);
  // $result = ob_get_clean();

  // file_put_contents('hoge.txt', $result);

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

  $json = json_encode($data, JSON_UNESCAPED_UNICODE);

  // echo '<pre>';
  // echo $json->formElements;
  // echo '</pre>';

  file_put_contents('form_data.json', $json);
} catch (JSONException $e) {
  // ステータスコードの設定
  header("HTTP/1.1 500 Internal Server Error");
  // Content-Typeの設定
  header("Content-Type: application/json; charset=utf-8");
  // レスポンスボディにエラーの詳細を記載
  $result = array('code'=>500, 'message'=>'An Error occured.','description'=>$e->getMessage());
  echo json_encode($result);
  exit;
}


header("HTTP/1.1 200 OK");
header("Content-Type: application/json; charset=utf-8");
$output = array(
  'message' => 'Files are saved.'
);

echo json_encode($output);