<?php

namespace App\Controllers;

use App\Controller;
use App\Request;
use App\Route;
use \AuthManager;

class AuthController extends Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->view->useMaster(false);
  }

  protected function checkAuthorization() {}

  public function actionLogin()
  {
    if(Request::isPost())
    {
      AuthManager::getInstance()->login();
      Route::redirect('/dashboard');
    }

    if(AuthManager::isAuthorized())
    {
      Route::redirect('/dashboard');
    }

    $this->view->generate('login');
  }

  public function actionLogout()
  {
    AuthManager::getInstance()->logout();
    $this->view->generate('login');
  }



}

 ?>
