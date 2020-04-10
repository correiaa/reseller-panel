<?php

use App\Config;
use App\SSP;
use App\Database;
use App\PdoConnection;
use \DatabaseCredits;

class Datatables
{
  private $credits;
  private $db;

  public function __construct()
  {
    $credits = DatabaseCredits::getInstance();

    $this->credits = [
      'host'  => $credits->getHost(),
      'db'    => $credits->getName(),
      'user'  => $credits->getUser(),
      'pass'  => $credits->getPass(),
    ];

    $this->db = new Database(new PdoConnection($credits));
  }

  public function getCustomers()
  {
    $table = 'customers';

    $columns = $this->prepareColumns(['id', 'account_number', 'stb_mac', 'login', 'end_date', 'status']);

    $currentUser = CurrentUser::getInstance();

    $where = ($currentUser->isAdmin() == true) ? '' : '`reseller_id` = ' . $currentUser->getId();

    $result = SSP::simple($_POST, $this->credits, $table, 'id', $columns, null, $where);

    return $result;
  }

  public function getResellers()
  {
    $table = 'users';

    $columns = [
      [
        'db'  => "id",
        'dt'  => 0,
        'field' => 'id',
      ],
      [
        'db'  => "name",
        'dt'  => 1,
        'field' => 'name',
      ],
      [
        'db'  => "balance",
        'dt'  => 2,
        'field' => 'balance',
      ],
      [
        'db'  => "last_login",
        'dt'  => 5,
        'field' => 'last_login',
      ],
      [
        'db'  => "status",
        'dt'  => 6,
        'field' => 'status',
      ],
    ];

  $result = SSP::simple($_POST, $this->credits, $table, 'id', $columns);
  foreach($result['data'] as &$row)
  {
    $total = $this->db->fetchColumn("SELECT COUNT(1) FROM `customers` WHERE `reseller_id` = ?", [$row[0]]); //total customers
    $active = $this->db->fetchColumn("SELECT COUNT(1) FROM `customers` WHERE `reseller_id` = ? AND status = 1", [$row[0]]); //active customers
    $row[3] = empty($total) ? 0 : $total;
    $row[4] = empty($active) ? 0 : $active;
  }

    return $result;
  }

  public function getTransactions()
  {
    $table = 'transactions';

    $columns = [
      [
        'db'  => "$table.id",
        'dt'  => 0,
        'field' => 'id',
      ],
      [
        'db'  => "$table.date",
        'dt'  => 1,
        'field' => 'date',
      ],
      [
        'db'  => "$table.amount",
        'dt'  => 2,
        'field' => 'amount',
      ],
      [
        'db'  => "$table.type",
        'dt'  => 3,
        'as'  => 'login',
        'field' => 'login',
      ],
      [
        'db'  => "`users`.name",
        'dt'  => 4,
        'field' => 'name',
      ],
      [
        'db'  => "$table.recipient_id",
        'dt'  => 5,
        'field' => 'recipient_id',
        'formatter' => function($d, $row) {
          if($row[3] == 'res_to_res')
          {
            $sql = "SELECT `login` FROM `users` WHERE `id` = ?";
          }
          elseif($row[3] == 'res_to_cus')
          {
            $sql = "SELECT `login` FROM `customers` WHERE `id` = ?";
          }

          $name = $this->db->fetchColumn($sql, [$d]);

          if(CurrentUser::getInstance()->isAdmin())
          {
            $href = ($row[3] == 'res_to_res') ? "/resellers/$d" : "/customers/$d";
            $output = '<a href="' . $href . '">' . $name . '</a>';
          }
          else
          {
            $output = $name;
          }

          return $output;
        }
      ],
    ];

    $join = "FROM `$table` JOIN `users` ON `$table`.`sender_id` = `users`.`id`";

    $currentUser = CurrentUser::getInstance();
    $id = $currentUser->getId();

    $where = ($currentUser->isAdmin() == true) ? '' : "`recipient_id` = $id AND `type` = \"res_to_res\" OR `sender_id` = $id AND `type` = \"res_to_cus\"";

    $result = SSP::simple($_POST, $this->credits, $table, 'id', $columns, $join, $where);

    return $result;
  }

  private function prepareColumns(array $names)
  {
    $columns = [];
    for($i = 0; $i < count($names); $i++)
    {
      $columns[] = [
        'db'  => $names[$i],
        'dt'  => $i
      ];
    }
    return $columns;
  }
}

 ?>
