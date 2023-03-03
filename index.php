<?php

require_once './vendor/autoload.php';

// Initialize dotenv

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Initialize router
$router = new \Bramus\Router\Router();

// Routing
$router->get('/', function() {
  
  // Initialize BuildForm
  $bf = new \App\BuildForm;
  
  echo "<form action=\"/confirm\" method=\"POST\">\n";
  echo $bf->loop_out();
  echo "<button type=\"submit\">確認</button>\n";
  echo '</form>';
});

$router->post('/confirm', function() {
  // Initialize confirm screen
  $confirm = new \App\Confirm;

  echo $confirm->render();

});
// Run Router
$router->run();