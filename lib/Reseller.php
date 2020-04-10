<?php

use App\DatabaseTrait;
use App\Logger;
use App\Exceptions\UserInputException;
use App\Exceptions\RolePrivilegueException;
use App\Exceptions\CreditsBalanceException;
use App\Config;

class Reseller
{
  private $db;

  use DatabaseTrait { getDatabase as private; }

  public function __construct()
  {
    $this->db = $this->getDatabase();
  }

  public function updateBalance($transactionAmount, $resellerId)
  {
    if(CurrentUser::getInstance()->isAdmin() == false)
    {
      $currentBalance = $this->db->fetchColumn("SELECT `balance` FROM `users` WHERE `id` = ?", [intval($resellerId)]);
      $newBalance = $currentBalance - floatval($transactionAmount);
      $this->db->update('users', ['id' => $resellerId, 'balance' => $newBalance]);
    }
  }

  public function add()
  {
    $filters = $this->getFiltersAdd();
    $data = filter_input_array(INPUT_POST, $filters);

    if(!isset($data['login'], $data['password']))
    {
      throw new UserInputException("Both login and password must be enterted: " . Logger::formatArrayToString($data), 1);
    }
    $data['parent_id'] = CurrentUser::getInstance()->getId();
    $resellerId = $this->db->insert('users', $data);

    if(floatval($data['balance']) > 0)
    {
      $transaction = [
        'sender_id' => CurrentUser::getInstance()->getId(),
        'recipient_id' => $resellerId,
        'type' => 'res_to_res',
        'amount' => $data['balance'],
      ];

      $this->db->insert('transactions', $transaction);
    }

    return $resellerId;
  }

  public function update($id)
  {
    $filters = $this->getFiltersUpdate();
    $data = filter_input_array(INPUT_POST, $filters);

    if(!isset($data['name']) || $data['balance'] < 0 || $data['password1'] !== $data['password2'])
    {
      throw new UserInputException("Incorrent reseller update data: " . Logger::formatArrayToString($data), 1);
    }

    $reseller = $this->getById($id);
    if(floatval($reseller['balance']) !== floatval($data['balance']) && CurrentUser::getInstance()->isAdmin() == false)
    {
      throw new RolePrivilegueException("Only admin is allowed to change the reseller's balance: " . Logger::formatArrayToString(
        ['current_balance' => $reseller['balance'], 'updated_balance' => $data['balance']]
      ), 1);
    }

    if($data['password1'] === $data['password2'] && !empty($data['password1']))
    {
      $data['password'] = password_hash($data['password1'], PASSWORD_BCRYPT);
    }

    unset($data['password1']);
    unset($data['password2']);

    $data['id'] = $id;

    unset($data['login']);

    $this->db->update('users', $data);

    if(floatval($reseller['balance']) !== floatval($data['balance']))
    {
      $amount = $reseller['balance'] < $data['balance'] ? $data['balance'] - $reseller['balance'] : ($reseller['balance'] - $data['balance']) * -1;
      $transaction = [
        'sender_id' => CurrentUser::getInstance()->getId(),
        'recipient_id' => $id,
        'type' => 'res_to_res',
        'amount' => $amount,
      ];

      $this->db->insert('transactions', $transaction);
    }
  }

  public function updateProfile()
  {
    $id = CurrentUser::getInstance()->getId();

    $filters = $this->getFiltersUpdate();
    $data = filter_input_array(INPUT_POST, $filters);

    if(!isset($data['name']) || $data['password1'] !== $data['password2'])
    {
      throw new UserInputException("Incorrent reseller update data: " . Logger::formatArrayToString($data), 1);
    }

    $reseller = $this->getById($id);

    if($data['password1'] === $data['password2'])
    {
      $data['password'] = password_hash($data['password1'], PASSWORD_BCRYPT);
      unset($data['password1']);
      unset($data['password2']);
    }

    $data['id'] = $id;

    unset($data['login']);
    unset($data['balance']);

    $this->db->update('users', $data);
  }

  public function getById($id)
  {
    return $this->db->fetch("SELECT * FROM `users` WHERE `id` = ?", [intval($id)]);
  }

  public function switchStatus($id, $status)
  {
    if(CurrentUser::getInstance()->getId() == $id)
    {
      return;
    }

    $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);
    $this->db->update("users", ['id' => intval($id), 'status' => $status == true ? 1 : 0]);
  }

  public function checkAvailableBalance($months, $resellerId, $customerId = null)
  {
    if($months == Config::get('free_trial_period', '2 days'))
    {
      return true;
    }

    if($customerId != null && is_int(intval($customerId)))
    {
      $date = $this->db->fetchColumn("SELECT `end_date` FROM `customers` WHERE `id` = ?", [$customerId]);
      if($months == $date)
      {
        return true;
      }
    }

    $reseller = $this->getById($resellerId);
    if($reseller['role'] == 'admin')
    {
      return true;
    }

    $months = intval($months);

    $balance = $reseller['balance'];

    if($balance < $months)
    {
      throw new CreditsBalanceException('Not enough credits.');
    }

    return true;
  }

  public function canManageCustomer($customerId)
  {
    if(CurrentUser::getInstance()->isAdmin() == true)
    {
      return true;
    }

    $resellerId = $this->db->fetchColumn("SELECT `reseller_id` from `customers` WHERE `id` = ?", [$customerId]);
    return $resellerId == CurrentUser::getInstance()->getId();
  }

  private function getFiltersAdd()
  {
    return [
      'name'            => FILTER_SANITIZE_STRING,
      'login'           => FILTER_SANITIZE_STRING,
      'password'        => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          return password_hash($value, PASSWORD_BCRYPT);
        }
      ],
      'balance'         => FILTER_SANITIZE_NUMBER_FLOAT,
      'status'          => FILTER_VALIDATE_BOOLEAN,
    ];
  }

  private function getFiltersUpdate()
  {
    return [
      'name'            => FILTER_SANITIZE_STRING,
      'login'           => FILTER_SANITIZE_STRING,
      'password1'       => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          return $value === $_POST['password2'] ? filter_var($value, FILTER_SANITIZE_STRING) : null;
        }
      ],
      'password2'       => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          return $value === $_POST['password1'] ? filter_var($value, FILTER_SANITIZE_STRING) : null;
        }
      ],
      'balance'         => FILTER_SANITIZE_NUMBER_FLOAT,
    ];
  }
}

 ?>
