<?php

namespace App;

class BasicAuth
{
  public static function auth($username, $password, $salt)
  {
    header('Cache-Control: no-cache, must-revalidate, max-age=0');
    $creds = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

    $isNotAuth = (
		    !$creds ||
		    !password_verify($salt.$_SERVER['PHP_AUTH_USER'], $username) ||
		    !password_verify($salt.$_SERVER['PHP_AUTH_PW'], $password)
	  );

    if ($isNotAuth)
    {
      header('HTTP/1.1 401 Authorization Required');
		  header('WWW-Authenticate: Basic realm="Access denied"');
		  exit;
	  }
  }
}

 ?>
