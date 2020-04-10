<?php

namespace App\Controllers;

use App\Controller;
use App\Config;
use App\SSP;
use App\Route;
use \PortalDataUpdater;
use \PortalTariffPlan;
use \Customer;
use \Transactions;
use \AuthManager;
use App\Request;
use \Reseller;
use \CurrentUser;
use App\Exceptions\CreditsBalanceException;

class ResellersController extends Controller
{
  private $reseller;

  public function __construct()
  {
    parent::__construct();
    $this->reseller = new Reseller();
  }

  private function currentUserMustBeAdmin($adminOnly)
  {
    if(boolval($adminOnly) == true && \CurrentUser::getInstance()->isAdmin() !== true)
    {
      $id = CurrentUser::getInstance()->getId();
      AuthManager::getInstance()->logout("Resellers controller: access denied for user [$id]");
    }
  }

  public function actionList()
  {
    $this->currentUserMustBeAdmin(true);
    if(Request::isPost())
    {
      $id = $this->reseller->add();
      Route::redirect("/resellers/$id");
    }

    $data = $this->commonData;
    $this->view->generate('resellers', $data);
  }

  public function actionStatus($id, $status)
  {
    $this->currentUserMustBeAdmin(true);
    $this->reseller->switchStatus($id, $status);
    Route::redirect('/resellers');
  }

  public function actionProfile()
  {
    if(Request::isPost())
    {
      $this->reseller->updateProfile();
      Route::redirect("/profile");
    }

    $id = CurrentUser::getInstance()->getId();

    $reseller = $this->reseller->getById($id);
    $transactions = (new Transactions())->getResellerTransactions($id);
    foreach($transactions as &$tr)
    {
      $tr['recipient'] = [
        'href' => ($tr['type'] == 'res_to_res') ? "/resellers/{$tr['recipient_id']}" : "/customers/{$tr['recipient_id']}",
        'name' => $tr['recipient_name'],
      ];

      $tr['type'] = ($tr['type'] == 'res_to_res') ? 'Admin to reseller' : 'Reseller to customer';
    }
    $lastLogin = $reseller['last_login'];

    $data = [
      'id'                  => $id,
      'login'               => $reseller['login'],
      'name'                => $reseller['name'],
      'balance'             => $reseller['balance'],
      'last_login'          => !empty($lastLogin) ? date("d M Y H:i:s", strtotime($lastLogin)) : 'No activity yet',
      'transactions'        => $transactions,
    ];

    $this->view->generate('profile', $data);
  }

  public function actionEdit($id)
  {
    if(CurrentUser::getInstance()->getId() == $id)
    {
      Route::redirect('/profile');
    }

    $this->currentUserMustBeAdmin(true);

    if(Request::isPost())
    {
      $this->reseller->update($id);
      Route::redirect("/resellers/$id");
    }

    $reseller = $this->reseller->getById($id);
    $transactions = (new Transactions())->getResellerTransactions($id);
    foreach($transactions as &$tr)
    {
      $tr['recipient'] = [
        'href' => ($tr['type'] == 'res_to_res') ? "/resellers/{$tr['recipient_id']}" : "/customers/{$tr['recipient_id']}",
        'name' => $tr['recipient_name'],
      ];

      $tr['type'] = ($tr['type'] == 'res_to_res') ? 'Admin to reseller' : 'Reseller to customer';
    }
    $lastLogin = $reseller['last_login'];

    $data = [
      'id'                  => $id,
      'login'               => $reseller['login'],
      'name'                => $reseller['name'],
      'balance'             => $reseller['balance'],
      'last_login'          => !empty($lastLogin) ? date("d M Y H:i:s", strtotime($lastLogin)) : 'No activity yet',
      'is_me'               => CurrentUser::getInstance()->getId() == $id,
      'transactions'        => $transactions,
    ];

    $this->view->generate('reseller', $data);
  }

  public function actionCheckAvailableBalance()
  {
    $months = isset($_POST['subscription']) && !isset($_POST['end_date']) ? $_POST['subscription'] : $_POST['end_date'];

    $customerId = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;

    try {
      $result = $this->reseller->checkAvailableBalance($months, CurrentUser::getInstance()->getId(), $customerId);
    } catch (CreditsBalanceException $e) {
      $result = $e->getPublicMessage();
    }

    $this->view->echoJson($result);
  }

}

 ?>
