<?php

namespace App\Mappers;

use \PDO;

class TransactionMapper extends \App\Mapper
{
  protected function getFieldsToUpdate(): array
  {
    return $this->getFieldsToInsert();
  }

  protected function getFieldsToInsert(): array
  {
    return [
      'amount'       => PDO::PARAM_INT,
      'sender_id'    => PDO::PARAM_INT,
      'recipient_id' => PDO::PARAM_INT,
      'type'         => PDO::PARAM_STR,
    ];
  }

  protected function getFieldsToUpdateOnDuplicateKey(): array
  {
    return $this->getFieldsToInsert();
  }
}

 ?>
