<?php

use App\Config;
use App\Mappers\CustomerMapper;
use App\Mappers\TransactionMapper;
use StalkerPortal\ApiV1\Exceptions\StalkerPortalException;
use App\Exceptions\CustomerIdException;
use App\DatabaseTrait as MyPdoTrait;
use App\Logger;

class Customer
{
  use DatabaseTrait { getPdoConnection as private; }
  use MyPdoTrait { getDatabase as private; }

  private $customerMapper;
  private $db;
  private $mypdo;

  public function __construct()
  {
    $this->customerMapper = new CustomerMapper(DatabaseCredits::getInstance());
    $this->db = $this->getPdoConnection();
    $this->mypdo = $this->getDatabase();
  }

  public function validateMac($mac, $customerId = null)
  {
    if(empty($mac))
    {
      return true;
    }

    $mac = strtoupper($mac);
    //check if mac format is valid
    if(filter_var($mac, FILTER_VALIDATE_MAC) !== $mac)
    {
      throw new CustomerIdException("MAC-address [$mac] format is not valid", CustomerIdException::MAC_FORMAT_NOT_VALID);
    }

    //check if mac is not already in use in the panel database
    $id = $this->mypdo->fetchColumn("SELECT `id` FROM `customers` WHERE `stb_mac` = ?", [$mac]);

    if($id == $customerId && intval($customerId) > 0)
    {
      return true;
    }
    elseif(!empty($id))
    {
      throw new CustomerIdException("MAC-address [$mac] is already in use", CustomerIdException::MAC_IN_USE);
    }

    //check if mac is not already in use in the portal
    $isUnique = PortalApiResources::getInstance()->getUsers()->isMacUnique($mac);

    if(!empty($id) || $isUnique != true)
    {
      throw new CustomerIdException("MAC-address [$mac] is already in use in portal", CustomerIdException::MAC_IN_USE);
    }

    return true;
  }

  public function validateLogin($login)
  {
    if(empty($login))
    {
      return true;
    }

    return $this->isLoginUnique($login);
  }

  private function isLoginUnique($login)
  {
    //check if login is not already in use in the panel database
    $count = $this->mypdo->fetchColumn("SELECT COUNT(1) FROM `customers` WHERE `login` = ?", [$login]);

    //check if mac is not already in use in the portal
    $isUnique = PortalApiResources::getInstance()->getUsers()->isLoginUnique($login);

    if($count > 0 || $isUnique != true)
    {
      throw new CustomerIdException("Login [$login] is already in use", CustomerIdException::LOGIN_IN_USE);
    }

    return true;
  }

  public function switchStatus($id, $status)
  {
    $status = filter_var($status, FILTER_VALIDATE_BOOLEAN);
    $stalkerId = $this->getLoginOrMac($id);

    try
    {
      PortalApiResources::getInstance()->getUsers()->switchStatus($stalkerId, $status);
    }
    catch(StalkerPortalException $e)
    {
      if($e->getMessage() !== 'Account not found')
      {
        throw new \Exception($e->getMessage(), 1);
      }
      $this->customerMapper->delete($id);
    }

  }

  public function generateLogin()
  {
    do {
      $login = mt_rand(000000, 999999);
    } while($this->isLoginUnique($login) != true);
    return ['login' => $login, 'password' => mt_rand(000000, 999999)];
  }

  public function add()
  {
    $filters = $this->getFiltersAdd();

    $data = filter_input_array(INPUT_POST, $filters);
    $data['end_date'] = date('Y-m-d H:i:s', strtotime('+' . $data['subscription']));

    if(empty($data['login']))
    {
      $login = $this->generateLogin();
      $data['login'] = $login['login'];
      $data['password'] = $login['password'];
    }

    $stalkerPortalUser = new StalkerPortalUser($data);
    $insertResult = PortalApiResources::getInstance()->getAccounts()->add($stalkerPortalUser);
    if($insertResult == true)
    {
      $customerObject = $this->customerMapper->createObject($data);
      $resellerId = CurrentUser::getInstance()->getId();
      $customerObject->setResellerId($resellerId);
      $this->customerMapper->insert($customerObject);

      $customerId = $customerObject->getId();
      if(!empty($customerId) && is_int(intval($customerId)))
      {
        $subscription = intval($data['subscription']);
        $amount = (Config::get('free_trial_period', '2 days') == $subscription) ? 0 : $subscription;
        $transaction = [
          'sender_id' => $resellerId,
          'recipient_id' => $customerId,
          'type' => 'res_to_cus',
          'amount' => $amount,
        ];

        $this->mypdo->insert('transactions', $transaction);

        if($amount != 0)
        {
          (new Reseller())->updateBalance($amount, $resellerId);
        }
      }
      return $customerId;
    }
    else
    {
      error_log("New user not inserted in portal, user data: " . Logger::formatArrayToString($data));
    }
  }

  public function update($id)
  {
    $customerModel = $this->customerMapper->find($id);

    $filters = $this->getFiltersUpdate();

    $data = filter_input_array(INPUT_POST, $filters);

    $currentEndDate = $customerModel->getEndDate();

    $credits = 0;
    $resellerId = CurrentUser::getInstance()->getId();

    $reseller = new Reseller();

    if(!empty($data['end_date']) && $data['end_date'] != $currentEndDate)
    {
      $credits = intval($data['end_date']);
      $reseller->checkAvailableBalance($credits, $resellerId);
      $extendFor = (empty($currentEndDate))
        ? strtotime('+' . $data['end_date'])
        : strtotime('+' . $data['end_date'], strtotime($currentEndDate));

      $data['end_date'] = date('Y-m-d H:i:s', $extendFor);
    }

    $data['login'] = $customerModel->getLogin();

    $stalkerPortalUser = new StalkerPortalUser($data);
    PortalApiResources::getInstance()->getUsers()->updateUser($stalkerPortalUser);

    if($credits > 0)
    {
      $transaction = [
        'sender_id' => $resellerId,
        'recipient_id' => $id,
        'type' => 'res_to_cus',
        'amount' => $credits,
      ];
      $this->mypdo->insert('transactions', $transaction);

      $reseller->updateBalance($credits, $resellerId);
    }

    $this->updatePassword($id, $data['password']);
  }

  public function delete($id)
  {
    $id = (int)$id;
    $customerModel = $this->customerMapper->find($id);

    $userId = ($customerModel->getLogin() == true) ? $customerModel->getLogin() : $customerModel->getStbMac();

    try
    {
      PortalApiResources::getInstance()->getUsers()->remove($userId);
    }
    catch(StalkerPortalException $e)
    {
      if($e->getMessage() !== 'Account not found')
      {
        throw new \Exception($e->getMessage(), 1);
      }
    }

    $this->customerMapper->delete($id);

  }

  public function get($id)
  {
    $id = (int)$id;

    $stalkerPortalUserId = $this->getLoginOrMac($id);

    if(empty($stalkerPortalUserId))
    {
      throw new \Exception("Can't find customer id = $id", 1);
    }

    $stalkerPortalUser = PortalApiResources::getInstance()->getUsers()->select($stalkerPortalUserId);

    if(Config::get('use_ministra_resellers', false) == false)
    {
      unset($stalkerPortalUser['reseller_id']);
      unset($stalkerPortalUser['reseller_name']);
    }

    unset($stalkerPortalUser['password']);

    $stalkerPortalUser['id'] = $id;
    $customerObject = $this->customerMapper->createObject($stalkerPortalUser);

    $resellerId = $this->mypdo->fetchColumn("SELECT `reseller_id` FROM `customers` WHERE `id` = ?", [$id]);
    $customerObject->setResellerId($resellerId);

    $this->customerMapper->update($customerObject);
    return $this->customerMapper->find($id);
  }

  private function getLoginOrMac($id)
  {
    $sql = "SELECT IF(`login` != '' AND `login` IS NOT NULL, `login`, `stb_mac`) FROM `customers` WHERE `id` = ?";
    return $this->mypdo->fetchColumn($sql, [$id]);
  }

  private function updatePassword($customerId, $password)
  {
    $this->mypdo->update('customers', ['password' => $password, 'id' => $customerId]);
  }

  private function getFiltersAdd()
  {
    return [
      'full_name'       => FILTER_SANITIZE_STRING,
      'login'           => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          $this->validateLogin($value);
          return $value;
        }
      ],
      'password'        => FILTER_SANITIZE_STRING,
      'stb_mac'         => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          $this->validateMac($value);
          return !empty($value) ? $value : '';
        }
      ],
      'account_number'  => FILTER_SANITIZE_STRING,
      'account_balance' => FILTER_SANITIZE_NUMBER_FLOAT,
      'tariff_plan'     => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          $tariffs = PortalTariffPlan::getInstance()->getAll($onlyExternalId = true);
          return (in_array($value, $tariffs)) ? $value : PortalTariffPlan::getInstance()->getDefault($onlyExternalId = true);
        }
      ],
      'end_date'        => FILTER_SANITIZE_STRING,
      'status'          => FILTER_VALIDATE_BOOLEAN,
      'subscription'    => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          (new Reseller())->checkAvailableBalance($value, CurrentUser::getInstance()->getId());
          return (in_array($value, Config::get('durations')) || Config::get('free_trial_period', '2 days') == $value) ? $value : 1;
        }
      ],
      'comment'         => FILTER_SANITIZE_STRING,
    ];
  }

  private function getFiltersUpdate()
  {
    return [
      'full_name'       => FILTER_SANITIZE_STRING,
      'phone'           => FILTER_SANITIZE_STRING,
      'password'        => FILTER_SANITIZE_STRING,
      'stb_mac'         => FILTER_VALIDATE_MAC,
      'account_number'  => FILTER_SANITIZE_STRING,
      'payment'         => FILTER_SANITIZE_NUMBER_FLOAT,
      'tariff_plan'     => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          $tariffs = PortalTariffPlan::getInstance()->getAll($onlyExternalId = true);
          return (in_array($value, $tariffs)) ? $value : PortalTariffPlan::getInstance()->getDefault($onlyExternalId = true);
        }
      ],
      'end_date'        => [
        'filter'  => FILTER_CALLBACK,
        'options' => function($value) {
          return (in_array($value, Config::get('durations')) || date('Y-m-d H:i:s', strtotime($value)) == $value) ? $value : null;
        }
      ],
      'status'          => FILTER_VALIDATE_BOOLEAN,
      'comment'         => FILTER_SANITIZE_STRING,
    ];
  }

}

 ?>

