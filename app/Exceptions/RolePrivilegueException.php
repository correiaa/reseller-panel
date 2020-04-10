<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class RolePrivilegueException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Not enough privilegues to perform the action.");
  }
}

 ?>
