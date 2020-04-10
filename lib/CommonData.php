<?php

use App\Database;
use App\PdoConnection;

class CommonData
{

  public static function get()
  {
    $admin = CurrentUser::getInstance()->isAdmin();
    $stats = new Statistics(new Database(new PdoConnection(DatabaseCredits::getInstance())));
    return [
      'active_customers'         => ($admin == true) ? $stats->getTotalActiveUsersNumber() : $stats->getMyActiveUsersNumber(),
      'soon_expired_customers'  => ($admin == true) ? $stats->getTotalSoonExpiredCustomersNumber() : $stats->getMySoonExpiredCustomersNumber(),
      'expired_customers'       => ($admin == true) ? $stats->getTotalExpiredCustomersNumber() : $stats->getMyExpiredCustomersNumber(),
      'balance'                 => ($admin == true) ? 'Infinite' : CurrentUser::getInstance()->getProfile()['balance'],
      'current_user_name'       => CurrentUser::getInstance()->getProfile()['name'],
    ];
  }
}

?>
