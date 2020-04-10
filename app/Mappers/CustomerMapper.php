<?php

namespace App\Mappers;

use \PDO;

class CustomerMapper extends \App\Mapper
{
  protected function getFieldsToUpdate(): array
  {
    return [
      'id'  => PDO::PARAM_INT,
      'account_number'  => PDO::PARAM_STR,
      'stb_mac'         => PDO::PARAM_STR,
      //'password'        => PDO::PARAM_STR,
      'end_date'        => PDO::PARAM_STR,
      'status'          => PDO::PARAM_BOOL,
      'full_name'       => PDO::PARAM_STR,
      'phone'           => PDO::PARAM_STR,
      'tariff_plan'     => PDO::PARAM_STR,
      'stb_sn'          => PDO::PARAM_STR,
      'stb_type'        => PDO::PARAM_STR,
      'online'          => PDO::PARAM_BOOL,
      'ip'              => PDO::PARAM_STR,
      'version'         => PDO::PARAM_STR,
      'comment'         => PDO::PARAM_STR,
      'last_active'     => PDO::PARAM_STR,
      'account_balance' => PDO::PARAM_STR,
      'reseller_id'     => PDO::PARAM_INT,
    ];
  }

  protected function getFieldsToInsert(): array
  {
    return [
      'login'           => PDO::PARAM_STR,
      'account_number'  => PDO::PARAM_STR,
      'stb_mac'         => PDO::PARAM_STR,
      'password'        => PDO::PARAM_STR,
      'end_date'        => PDO::PARAM_STR,
      'status'          => PDO::PARAM_BOOL,
      'full_name'       => PDO::PARAM_STR,
      'phone'           => PDO::PARAM_STR,
      'tariff_plan'     => PDO::PARAM_STR,
      'stb_sn'          => PDO::PARAM_STR,
      'stb_type'        => PDO::PARAM_STR,
      'online'          => PDO::PARAM_BOOL,
      'ip'              => PDO::PARAM_STR,
      'version'         => PDO::PARAM_STR,
      'comment'         => PDO::PARAM_STR,
      'last_active'     => PDO::PARAM_STR,
      'account_balance' => PDO::PARAM_STR,
      'reseller_id'     => PDO::PARAM_INT,
    ];
  }

  protected function getFieldsToUpdateOnDuplicateKey(): array
  {
    return [
      'account_number'  => PDO::PARAM_STR,
      'end_date'        => PDO::PARAM_STR,
      'status'          => PDO::PARAM_BOOL,
      'full_name'       => PDO::PARAM_STR,
      'phone'           => PDO::PARAM_STR,
      'tariff_plan'     => PDO::PARAM_STR,
      'stb_sn'          => PDO::PARAM_STR,
      'stb_type'        => PDO::PARAM_STR,
      'online'          => PDO::PARAM_BOOL,
      'ip'              => PDO::PARAM_STR,
      'version'         => PDO::PARAM_STR,
      'comment'         => PDO::PARAM_STR,
      'last_active'     => PDO::PARAM_STR,
      'account_balance' => PDO::PARAM_STR,
      'reseller_id'     => PDO::PARAM_INT,
    ];
  }
}

 ?>
