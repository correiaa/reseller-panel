<?php

namespace App\Controllers;

use App\Controller;
use \Statistics;


class ChartsController extends Controller
{
  public function actionIndex()
  {
    $data = [

    ];

    $this->view->generate('charts', $data);
  }

  public function actionLastYearIncome()
  {
    $stats = new Statistics();
    $stats = $stats->getLastYearIncomeStats();
    header("Content-type: application/json");
    echo json_encode($stats);
  }
}

 ?>
