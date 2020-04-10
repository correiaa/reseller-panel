<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class ViewException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return _("Unable to render the page. Please, contact website administrator.");
  }
}

 ?>
