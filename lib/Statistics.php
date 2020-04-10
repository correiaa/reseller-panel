<?php

use App\Config;
use App\Database;

class Statistics
{
  private $db;

  public function __construct(Database $db)
  {
    $this->db = $db;
  }

  public function getMyUsersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers` WHERE `reseller_id` = ?";
    return $this->getValue($sql);
  }

  public function getTotalUsersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers`";
    return $this->getValue($sql, false);
  }

  public function getTotalActiveUsersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers` WHERE `status` = 1";
    return $this->getValue($sql, false);
  }

  public function getMyActiveUsersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers` WHERE `status` = 1 AND `reseller_id` = ?";
    return $this->getValue($sql);
  }

  public function getMyExpiredCustomersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers` WHERE `reseller_id` = ? AND status = 0";
    return $this->getValue($sql);
  }

  public function getTotalExpiredCustomersNumber()
  {
    $sql = "SELECT COUNT(1) FROM `customers` WHERE status = 0";
    return $this->getValue($sql, false);
  }

  public function getMySoonExpiredCustomersNumber()
  {
    $days = intval(Config::get('days_to_expire'), 3);
    $sql = "SELECT COUNT(1) FROM `customers` WHERE TIMESTAMPDIFF(day, NOW(), end_date) < $days AND `status` = 1 AND `reseller_id` = ?";
    return $this->getValue($sql);
  }

  public function getTotalSoonExpiredCustomersNumber()
  {
    $days = intval(Config::get('days_to_expire'), 3);
    $sql = "SELECT COUNT(1) FROM `customers` WHERE TIMESTAMPDIFF(day, NOW(), end_date) < $days AND `status` = 1";
    return $this->getValue($sql, false);
  }

  private function getValue($sql, $onlyMy = true)
  {
    $args = $onlyMy == true ? [CurrentUser::getInstance()->getId()] : [];
    $number = $this->db->fetchColumn($sql, $args);
    return ($number == true) ? $number : 0;
  }

}


 ?>
