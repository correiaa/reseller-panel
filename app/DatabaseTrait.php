<?php

namespace App;

use App\Database;
use App\PdoConnection;
use \DatabaseCredits;

trait DatabaseTrait
{
  public function getDatabase()
  {
    return new Database(new PdoConnection(DatabaseCredits::getInstance()));
  }
}

 ?>
