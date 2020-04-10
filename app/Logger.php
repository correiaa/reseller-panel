<?php

namespace App;

class Logger
{
  public static function formatArrayToString(array $data)
  {
    $output = '[';
    foreach($data as $key => $value)
    {
      $output .= "$key => $value, ";
    }
    $output = rtrim($output, ', ') . ']';
    return $output;
  }
}

 ?>
