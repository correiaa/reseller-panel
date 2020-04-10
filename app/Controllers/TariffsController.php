<?php

namespace App\Controllers;

use App\Controller;
use \TariffPlanFactory;

class TariffsController extends Controller
{
  public function actionGetExpireDate()
  {
    $filters = [
      'account_balance' => FILTER_SANITIZE_STRING,
      'tariff_plan'     => FILTER_SANITIZE_STRING,
    ];
    $data = filter_input_array(INPUT_POST, $filters);

    $tariffPlan = TariffPlanFactory::create($data['tariff_plan']);
    $expireDate = $tariffPlan->getExpireDate($data['account_balance']);
    echo $expireDate;
  }


}

 ?>
