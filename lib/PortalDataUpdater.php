<?php

use App\Config;
use App\Mappers\CustomerMapper;
use App\DatabaseTrait as MyPdoTrait;

class PortalDataUpdater
{
  use MyPdoTrait { getDatabase as private; }

  private $db;

  private static $instance;

  private function __clone() {}

  private function __construct()
  {
    $this->db = $this->getDatabase();
  }

  public static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function setLatestUpdate()
  {
    $_SESSION['last_update'] = time();
  }

  public function getLatestUpdate()
  {
    return (isset($_SESSION['last_update'])) ? $_SESSION['last_update'] : false;
  }

  private function isUpdateNeeded(): bool
  {
    return !isset($_SESSION['last_update'])
            || (time() - $_SESSION['last_update']) > Config::get('update_period') * 3600
            || Config::get('always_actual') == true;
  }

  public function check()
  {
    if($this->isUpdateNeeded() == true)
    {
      $this->update();
    }
  }

  public function forceUpdate()
  {
    $this->update();
  }

  private function update()
  {
    $usersResource = PortalApiResources::getInstance()->getAccounts();
    $users = $usersResource->select();

    $customerMapper = new CustomerMapper(DatabaseCredits::getInstance());

    foreach ($users as $userData)
    {
      if(Config::get('use_ministra_resellers', false) == false)
      {
        unset($userData['reseller_id']);
        unset($userData['reseller_name']);
      }

      $sql = "SELECT `reseller_id` FROM `customers` WHERE `%s` = ?";
      if(isset($userData['login']))
      {
        $resellerId = $this->db->fetchColumn(sprintf($sql, 'login'), [$userData['login']]);
      }
      elseif(isset($userData['stb_mac']))
      {
        $resellerId = $this->db->fetchColumn(sprintf($sql, 'stb_mac'), [$userData['stb_mac']]);
      }
      else
      {
        continue;
      }

      $customerObject = $customerMapper->createObject($userData);
      $customerObject->setResellerId($resellerId);
      $customerMapper->insertOrUpdate($customerObject);
    }
    $this->setLatestUpdate();
  }



}

 ?>
