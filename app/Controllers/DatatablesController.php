<?php

namespace App\Controllers;

use App\Controller;
use \Datatables;

class DatatablesController extends Controller
{
  private $datatables;

  public function __construct()
  {
    parent::__construct();
    $this->datatables = new Datatables();
  }

  public function actionCustomers()
  {
    $customers = $this->datatables->getCustomers();

    $this->view->echoJson($customers);
  }

  public function actionResellers()
  {
    $resellers = $this->datatables->getResellers();

    $this->view->echoJson($resellers);
  }

  public function actionTransactions()
  {
    $transactions = $this->datatables->getTransactions();

    $this->view->echoJson($transactions);
  }


}

 ?>
