<?php

namespace App\Exceptions;

use App\MyExceptionInterface;

class CustomerIdException extends \Exception implements MyExceptionInterface
{
  const MAC_FORMAT_NOT_VALID = 100;
  const MAC_IN_USE = 101;
  const LOGIN_IN_USE = 102;

  public function getPublicMessage()
  {
    $message = null;

    switch($this->getCode())
    {
      case self::MAC_FORMAT_NOT_VALID:
        $message = 'MAC-address format is not valid';
        break;
      case self::MAC_IN_USE:
        $message = 'MAC-address is already in use';
        break;
      case self::LOGIN_IN_USE:
        $message = 'Login is already in use';
        break;
      default:
        $message = 'Portal customer ID is not valid';
        break;
    }
    return $message;
  }
}

 ?>
