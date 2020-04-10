<?php

namespace App\Models;

use App\Model;

class CustomerModel extends Model
{
  public function setEndDate($date)
  {
    $this->set('end_date', $date != '0000-00-00 00:00:00' ? $date : '');
  }
}


 ?>
