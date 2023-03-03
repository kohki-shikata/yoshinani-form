<?php
$formData = $_POST;
$formSettings = file_get_contents('./form_data.json');
$form_settings = json_decode($formSettings);

function get_form_element($value, $array) {
  $key = array_search($value, $array);
  // var_dump($key);
  return $key;
}
// var_dump($formData);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <form action="./mail.php" method="POST">
  <table>
    <tbody>
      <?php
        foreach($formData as $key => $value) {
          $k = get_form_element($key, array_column($form_settings->formElements, 'name'));
          if($key && $value) {
          ?>
          <tr>
            <th>
              <?php
                if(isset($form_settings->formElements[$k]->label)) {
                  echo htmlspecialchars($form_settings->formElements[$k]->label);
                } else if(isset($form_settings->formElements[$k]->title)) {
                  echo htmlspecialchars($form_settings->formElements[$k]->title);
                } else {
                  echo htmlspecialchars($key);
                }
              ?>
            </th>
            <td>
              <?php
              if(isset($form_settings->formElements[$k]->choice_text)) {

                // echo array_search($value, $form_settings->formElements[$k]->choise_text);
              } else {
                if(gettype($value) === 'string') {
                  echo htmlspecialchars($value);
                } else if(gettype($value) === 'array') {
                  echo htmlspecialchars(array_search($form_settings->formElements[$k]->label,$value));
                }
              }
              ?>
              <input type="hidden" name="<?php echo $key;?>" value="<?php echo htmlspecialchars($value[0]);?>" />
              <input type="hidden" name="label_name" value="<?php echo htmlspecialchars($form_settings->formElements[$k]->label);?>" />
            </td>
          </tr>
          <?php
          }
        }
      ?>
    </tbody>
  </table>
  <button type="button">戻って修正</button>
  <button type="submit">この内容で送信</button>
  </form>
</body>
</html>