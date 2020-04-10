<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class MyPdoException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Error processing database query.");
  }
}

 ?>
