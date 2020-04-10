<?php

namespace App\Controllers;

use App\Controller;
use App\Config;
use App\SSP;

class TransactionsController extends Controller
{
  public function actionList()
  {
    $this->view->generate('transactions', $this->commonData);
  }


}

 ?>
