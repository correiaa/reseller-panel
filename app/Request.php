<?php

namespace App;

class Request
{
  public static function getUserInputData()
  {
    $data = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      $data = $_POST;
    }
    elseif($_SERVER['REQUEST_METHOD'] == 'GET')
    {
      $data = $_GET;
    }
    return $data;
  }

  public static function isAjax()
  {
    return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' );
  }

  public static function isPost()
  {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
  }

  public static function isPut()
  {
    return $_SERVER['REQUEST_METHOD'] === 'PUT';
  }

}

 ?>
