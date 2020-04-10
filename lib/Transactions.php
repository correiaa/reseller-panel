<?php

use App\Mappers\TransactionMapper;
use App\Models\TransactionModel;
use App\DatabaseTrait;

class Transactions
{
  use DatabaseTrait { getDatabase as private; }


  private $db;
  public function __construct()
  {
    $this->db = $this->getDatabase();
  }

  public function insertTransaction($customerId, $amount)
  {
    $sql = "INSERT INTO `transactions`(`customer_id`, `amount`) VALUES(?,?)";
    $stmnt = $this->db->prepare($sql);
    $stmnt->execute([intval($customerId), floatval($amount)]);
  }

  public function getCustomerTransactions($customerId)
  {
    $sql = 'SELECT * FROM `transactions` WHERE `type` = "res_to_cus" AND `recipient_id` = ?';
    return $this->db->fetchAll($sql, [intval($customerId)]);
  }

  public function getResellerTransactions($resellerId)
  {
    $sql = 'SELECT `transactions`.*, `users`.`name` AS sender_name
            FROM `transactions`
            JOIN `users` ON `transactions`.`sender_id` = `users`.`id`
            WHERE `sender_id` = ? OR (`recipient_id` = ? AND `type` = "res_to_res")';
    $id = intval($resellerId);
    $transactions = $this->db->fetchAll($sql, [$id, $id]);
    foreach($transactions as &$tr)
    {
      if($tr['type'] == 'res_to_cus')
      {
        $sql = "SELECT `full_name` FROM `customers` WHERE `id` = ?";
      }
      elseif($tr['type'] == 'res_to_res')
      {
        $sql = "SELECT `name` FROM `users` WHERE `id` = ?";
      }
      $tr['recipient_name'] = $this->db->fetchColumn($sql, [$tr['recipient_id']]);
    }
    return $transactions;
  }

  public function getAllTransactions()
  {
    $sql = 'SELECT `transactions`.*, `users`.`name` AS sender_name
            FROM `transactions`
            JOIN `users` ON `transactions`.`sender_id` = `users`.`id`';
    $transactions = $this->db->fetchAll($sql);
    foreach($transactions as &$tr)
    {
      if($tr['type'] == 'res_to_cus')
      {
        $sql = "SELECT `full_name` FROM `customers` WHERE `id` = ?";
      }
      elseif($tr['type'] == 'res_to_res')
      {
        $sql = "SELECT `name` FROM `users` WHERE `id` = ?";
      }
      $tr['recipient_name'] = $this->db->fetchColumn($sql, [$tr['recipient_id']]);
    }
    return $transactions;
  }

  public function getTotalCustomerIncome($customerId)
  {
    $sql = "SELECT SUM(`amount`) FROM `transactions` WHERE `customer_id` = ?";
    $stmnt = $this->db->prepare($sql);
    $stmnt->execute([intval($customerId)]);
    return $stmnt->fetchColumn();
  }
}


 ?>
