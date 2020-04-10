<?php

use StalkerPortal\ApiV1\Interfaces\Account;

class StalkerPortalUser implements Account
{
    public $mac;
    public $login;
    public $password;
    public $accountNumber;
    public $status;
    public $tariff;
    public $comment;
    public $expDate;
    public $balance;
    public $name;

    public function __construct(array $data)
    {
      $this->mac = isset($data['stb_mac']) ? $data['stb_mac'] : '';
      $this->login = isset($data['login']) ? $data['login'] : '';
      $this->password = isset($data['password']) ? $data['password'] : '';
      $this->accountNumber = isset($data['account_number']) ? $data['account_number'] : '';
      $this->status = isset($data['status']) ? $data['status'] : '';
      $this->tariff = isset($data['tariff_plan']) ? $data['tariff_plan'] : '';
      $this->comment = isset($data['comment']) ? $data['comment'] : '';
      $this->expDate = isset($data['end_date']) ?? $data['end_date'] != '0000-00-00 00:00:00' ? $data['end_date'] : '';
      $this->balance = isset($data['account_balance']) ? $data['account_balance'] : '';
      $this->name = isset($data['full_name']) ? $data['full_name'] : '';
    }

    public function getMac()
    {
        return $this->mac;
    }

    public function getLogin()
    {
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getTariffPlanExternalId()
    {
        return $this->tariff;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getExpireDate()
    {
        return $this->expDate;
    }

    public function getAccountBalance()
    {
        return $this->balance;
    }

    public function getFullName()
    {
        return $this->name;
    }
}


 ?>
