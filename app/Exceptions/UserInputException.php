<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class UserInputException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Incorrect user input.");
  }
}

 ?>
