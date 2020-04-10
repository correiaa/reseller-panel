<?php

namespace App;

use \CommonData;
use \AuthManager;

abstract class Controller
{
  protected $view;
  protected $commonData;

  public function __construct()
  {
    $this->checkAuthorization();
    $this->view = new View();
    if(AuthManager::isAuthorized() == true)
    {
      $this->commonData = CommonData::get();
    }
  }

  protected function checkAuthorization()
  {
    if(AuthManager::isAuthorized() != true)
    {
      Route::redirect('/logout');
    }
  }
}

 ?>
