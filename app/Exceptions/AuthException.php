<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class AuthException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Authorization failed.");
  }
}

 ?>
