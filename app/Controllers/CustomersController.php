<?php

namespace App\Controllers;

use App\Controller;
use App\Config;
use App\SSP;
use App\Route;
use App\Logger;
use \PortalDataUpdater;
use \PortalTariffPlan;
use \Customer;
use \Reseller;
use \Transactions;
use \CurrentUser;
use App\Exceptions\CustomerIdException;
use App\Exceptions\RolePrivilegueException;

class CustomersController extends Controller
{
  private $customerManager;

  public function __construct()
  {
    parent::__construct();
    $this->customerManager = new Customer();
  }

  public function actionValidateMac()
  {
    $mac = $_POST['stb_mac'];
    $customerId = isset($_POST['customer_id']) ? $_POST['customer_id'] : null;

    try {
      $result = $this->customerManager->validateMac($mac, $customerId);
    } catch (CustomerIdException $e) {
      $result = $e->getPublicMessage();
    }

    $this->view->echoJson($result);
  }

  public function actionValidateLogin()
  {
    $login = $_POST['login'];

    try {
      $result = $this->customerManager->validateLogin($login);
    } catch (CustomerIdException $e) {
      $result = $e->getPublicMessage();
    }

    $this->view->echoJson($result);
  }

  public function actionList()
  {
    $updater = PortalDataUpdater::getInstance()->check();
    $data = $this->commonData;
    $this->view->generate('customers', $data);
  }

  public function actionAdd()
  {
    $id = $this->customerManager->add();
    Route::redirect("/customers/$id");
  }

  public function actionForceUpdate()
  {
    PortalDataUpdater::getInstance()->forceUpdate();
    Route::redirect('/');
  }

  public function actionDelete($id)
  {
    $this->canManageCustomer($id);
    $this->customerManager->delete($id);
    Route::redirect('/customers');
  }

  private function canManageCustomer($id)
  {
    if((new Reseller())->canManageCustomer($id) == false)
    {
      $errorData = [
        'reseller_id' => CurrentUser::getInstance()->getId(),
        'customer_id' => $id
      ];

      throw new RolePrivilegueException("Reseller is not allowed to manage customer: " . Logger::formatArrayToString($errorData), 1);
    }
  }

  public function actionStatus($id, $status)
  {
    $this->canManageCustomer($id);
    $this->customerManager->switchStatus($id, $status);
    Route::redirect('/customers');
  }

  public function actionEdit($id)
  {
    $this->canManageCustomer($id);

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
      $this->customerManager->update($id);
    }

    $customer = $this->customerManager->get($id);
    $lastActive = $customer->getLastActive();

    $data = [
      'id'                  => $id,
      'login'               => $customer->getLogin(),
      'password'            => $customer->getPassword(),
      'name'                => $customer->getFullName(),
      'phone'               => $customer->getPhone(),
      'account_number'      => $customer->getAccountNumber(),
      'tariff_plan'         => $customer->getTariffPlan(),
      'serial_number'       => $customer->getStbSn(),
      'stb_mac'             => $customer->getStbMac(),
      'model'               => $customer->getStbType(),
      'status'              => $customer->getStatus(),
      'is_online'           => $customer->getOnline(),
      'ip'                  => $customer->getIp(),
      'software_version'    => $customer->getVersion(),
      'comment'             => $customer->getComment(),
      'end_date'            => $customer->getEndDate(),
      'last_active'         => !empty($lastActive) ? date("d M Y H:i:s", strtotime($lastActive)) : 'No activity yet',
      'tariffs'             => PortalTariffPlan::getInstance()->getAll(),
      'durations'           => Config::get('durations'),
    ];

    $this->view->generate('customers.edit', $data);
  }

}

 ?>
