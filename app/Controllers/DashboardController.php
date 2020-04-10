<?php

namespace App\Controllers;

use App\Controller;
use App\Config;
use \PortalApiResources;
use \PortalTariffPlan;
use Http\HttpClient;
use \PortalDataUpdater;
use \CommonData;

class DashboardController extends Controller
{
  public function actionIndex()
  {
    $updater = PortalDataUpdater::getInstance();
    $updater->check();
    $data = $this->commonData;
    //$data['latest_update'] = ($data['latest_update'] === false) ? 'Data outdated' : 'Updated '.$data['latest_update'].' minutes ago';

    $data['tariffs'] = PortalTariffPlan::getInstance()->getAll();
    $data['durations'] = Config::get('durations');
    $data['free_trial_period'] = Config::get('free_trial_period', '2 days');
    array_unshift($data['durations'], $data['free_trial_period']);
    $data['days_to_expire'] = Config::get('days_to_expire');

    $this->view->generate('dashboard', $data);
  }
}

 ?>
