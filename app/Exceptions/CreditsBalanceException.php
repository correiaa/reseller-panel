<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class CreditsBalanceException extends \Exception implements MyExceptionInterface
{
  public function getPublicMessage()
  {
    return $this->getMessage();
  }
}

 ?>
