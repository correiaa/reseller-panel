<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class ModelException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Backend error. Please, contact website administrator.");
  }
}

 ?>
