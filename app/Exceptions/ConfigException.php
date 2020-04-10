<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class ConfigException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Backend error. Please, contact website administrator.");
  }
}

 ?>
