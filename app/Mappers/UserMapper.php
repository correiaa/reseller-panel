<?php

namespace App\Mappers;

use \PDO;

class UserMapper extends \App\Mapper
{
  protected function getFieldsToUpdate(): array
  {
    return [
      'id'          => PDO::PARAM_INT,
      'name'        => PDO::PARAM_STR,
      'password'    => PDO::PARAM_STR,
      'parent_id'   => PDO::PARAM_INT,
      'balance'     => PDO::PARAM_INT,
      'last_login'  => PDO::PARAM_STR,
    ];
  }

  protected function getFieldsToInsert(): array
  {
    return [
      'name'        => PDO::PARAM_STR,
      'login'       => PDO::PARAM_STR,
      'password'    => PDO::PARAM_STR,
      'parent_id'   => PDO::PARAM_INT,
      'balance'     => PDO::PARAM_INT,
      'role'        => PDO::PARAM_STR,
      'last_login'  => PDO::PARAM_STR,
    ];
  }

  protected function getFieldsToUpdateOnDuplicateKey(): array
  {
    return $this->getFieldsToUpdate();
  }
}

 ?>
