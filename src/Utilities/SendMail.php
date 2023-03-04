<?php

namespace App\Utilities;

require_once __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../vendor/phpmailer/phpmailer/language/phpmailer.lang-ja.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Dotenv\Dotenv as Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

use EasyCSRF\Exceptions\InvalidCsrfTokenException as InvalidCsrfTokenException;

// ToDo: Need implement conditional state with language configuration.
mb_language('japanese');
mb_internal_encoding('UTF-8');

use App\Utilities\BuildForm as BuildForm;

class SendMail extends BuildForm {
  protected $formData;
  private $mail;

  public function __construct() {
    parent::__construct();
    $this->formData = $_POST; // TODO: Need validation.
    
    $this->mail = new PHPMailer(true);

    $this->mail->CharSet = "iso-2022-jp";
    $this->mail->Encoding = "7bit";
    $this->mail->setLanguage('ja', 'vendor/phpmailer/phpmailer/language/');
  }

  public function render_complete() {
    $template = $this->twig->load('/page/complete.html.twig');
    $data = [
      // 'data' => $this->formData,
      'state' => 'complete',
      // 'form_settings' => $this->initial_settings,
    ];
    return $template->render($data);
  }

  public function sendMail() {
    try {
      session_start();
      $this->csrf->check('new_token', $this->formData['csrf_token']);
      unset($this->formData['csrf_token']);
      try {
        // Mail server settings
        // $this->mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable debug mode, if you do not want to use this, please comment out this line.
        $this->mail->isSMTP(); // Use SMTP
        // $this->mail->isMail(); // Use PHP Mail Function
        $this->mail->Host = $_ENV['SMTP_HOST']; // Hostname of SMTP server
        if($this->initial_settings->smtpAuth) {
          $this->mail->SMTPAuth = true; // Enable SMTP Authentication
        } else {
          $this->mail->SMTPAuth = false;
        }
        $this->mail->Username = $_ENV['SMTP_USERNAME']; // SMTP Username
        $this->mail->Password = $_ENV['SMTP_PASSWORD']; // SMTP Password
        if($this->initial_settings->smtpEncryption) {
          $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Enable encryption
        }
        $this->mail->Port = $_ENV['SMTP_PORT']; // Set the port for SMTP
      
        // Recipient settings
        $this->mail->setFrom('sender@example.com', mb_encode_mimeheader('John Doe')); // Sender mail address and name.
        $this->mail->addAddress($_ENV['RECIPIENT_ADDRESS'], mb_encode_mimeheader($_ENV['RECIPIENT_NAME'])); // Recipient mail address and name.
        $this->mail->addReplyTo('info@example.com', mb_encode_mimeheader('ReplyTo')); // The address to reply.
        $this->mail->addCC('ccuser@example.com'); // Set CC recipient.
        
        // Content settings
        $this->mail->isHTML(true); // Use HTML mail or not.
        $this->mail->Subject = mb_encode_mimeheader('The mail subject'); // The subject for the mail.
        
        // Build text
        $main_recipient_template = $this->twig->load('/mail/recipient.twig');
        $data = [
          'recipient_name' => $_ENV['RECIPIENT_NAME'] ? $_ENV['RECIPIENT_NAME'] : null,
          'data' => $this->formData,
        ];
  
        // var_dump($main_recipient_template->render($data));
        $this->mail->Body = mb_convert_encoding($main_recipient_template->render($data), "JIS", "UTF-8"); // The body message for the mail.
        $this->mail->AltBody = mb_convert_encoding($main_recipient_template->render($data), "JIS", "UTF-8"); // The body message text for the mail.
      
        // echo $body;
        $this->mail->send(); // Send this message!
      
        // redirect to complete screen
        header("Location:/complete");
        // $this->render_complete();
      
      } catch (Exception $e) {
        echo 'Message could not be send. Mailer Error: {$mail->ErrorInfo}';
      }

    } catch(InvalidCsrfTokenException $e) {
      echo $e->getMessage();
    }
    
  }
}















