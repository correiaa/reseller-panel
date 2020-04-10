<?php

session_start();

try {

  require_once __DIR__ . '/../config/autoload.php';
  require_once __DIR__ . '/../vendor/autoload.php';

  $route = new App\Route();
  $route->run();

}
catch(App\Exceptions\AuthException $e)
{
  $error = $e->getMessage();
  error_log($error);
  $route::redirect('/login');
}

catch(StalkerPortal\ApiV1\Exceptions\StalkerPortalException $e)
{
  $view = new App\View();
  $error = $e->getMessage() . PHP_EOL . $e->getTraceAsString();
  error_log($error);
  $message = 'Ministra Portal Error';
  $view->generate('error', ['message' => $message]);
}

catch(Exception $e)
{
  $view = new App\View();
  $error = $e->getMessage() . PHP_EOL . $e->getTraceAsString();
  error_log($error);
  $message = 'Error processing request. Please contect panel administrator.';
  $view->generate('error', ['message' => $message]);
}

finally
{
  exit;
}





 ?>
