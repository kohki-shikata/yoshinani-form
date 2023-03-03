<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

mb_language('japanese');
mb_internal_encoding('UTF-8');

$formData = $_POST;

$mail = new PHPMailer(true);

$mail->CharSet = "iso-2022-jp";
$mail->Encoding = "7bit";

$mail->setLanguage('ja', 'vendor/phpmailer/phpmailer/language/');

try {
  // Mail server settings
  // $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable debug mode, if you do not want to use this, please comment out this line.
  $mail->isSMTP(); // Use SMTP
  // $mail->Host = 'sandbox.smtp.mailtrap.io'; // Hostname of SMTP server
  $mail->Host = $_ENV['SMTP_HOST']; // Hostname of SMTP server
  $mail->SMTPAuth = true; // Enable SMTP Authentication
  // $mail->Username = 'bd111e75ecfbb7'; // SMTP Username
  $mail->Username = $_ENV['SMTP_USERNAME']; // SMTP Username
  // $mail->Password = '33c439ebf40d1d'; // SMTP Password
  $mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP Password
  // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable encryption
  // $mail->Port = 2525; // Set the port for SMTP
  $mail->Port = $_ENV['SMTP_PORT']; // Set the port for SMTP

  // Recipient settings
  $mail->setFrom('sender@example.com', mb_encode_mimeheader('John Doe')); // Sender mail address and name.
  $mail->addAddress($_ENV['RECIPIENT_ADDRESS'], mb_encode_mimeheader($_ENV['RECIPIENT_NAME'])); // Recipient mail address and name.
  $mail->addReplyTo('info@example.com', mb_encode_mimeheader('ReplyTo')); // The address to reply.
  $mail->addCC('ccuser@example.com'); // Set CC recipient.
  
  // Content settings
  $mail->isHTML(true); // Use HTML mail or not.
  $mail->Subject = mb_encode_mimeheader('The mail subject'); // The subject for the mail.

  $body = '';
  foreach($formData as $key => $value) {
    if($key && $value) {
      $body .= <<<EOF
      ◆{$key}
      {$value}
      \n
      EOF;
    }
  };
  $mail->Body = mb_convert_encoding($body, "JIS", "UTF-8"); // The body message for the mail.
  $mail->AltBody = mb_convert_encoding($body, "JIS", "UTF-8"); // The body message text for the mail.

  // echo $body;
  $mail->send(); // Send this message!

  echo 'Message has been sent';

} catch (Exception $e) {
  echo 'Message could not be send. Mailer Error: {$mail->ErrorInfo}';
}