<?php

use App\Config;
use Http\HttpClient;
use StalkerPortal\ApiV1\Resources\Accounts;
use StalkerPortal\ApiV1\Resources\Users;
use StalkerPortal\ApiV1\Resources\Stb;
use StalkerPortal\ApiV1\Resources\Itv;
use StalkerPortal\ApiV1\Resources\ItvSubscription;
use StalkerPortal\ApiV1\Resources\ServicesPackage;
use StalkerPortal\ApiV1\Resources\StbMsg;
use StalkerPortal\ApiV1\Resources\SendEvent;
use StalkerPortal\ApiV1\Resources\Tariffs;


class PortalApiResources
{
  private $credits;

  private static $instance = null;

  private function __construct(HttpClient $credits)
  {
    $this->credits = $credits;
  }

  private function __clone() {}

  public static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self(new HttpClient(Config::get('api_url'), Config::get('api_login'), Config::get('api_password')));
    }
    return self::$instance;
  }

  public function getAccounts()
  {
    return new Accounts($this->credits);
  }

  public function getUsers()
  {
    return new Users($this->credits);
  }

  public function getStb()
  {
    return new Stb($this->credits);
  }

  public function getItv()
  {
    return new Itv($this->credits);
  }

  public function getItvSubscription()
  {
    return new ItvSubscription($this->credits);
  }

  public function getServicesPackage()
  {
    return new ServicesPackage($this->credits);
  }

  public function getStbMsg()
  {
    return new StbMsg($this->credits);
  }

  public function getSendEvent()
  {
    return new SendEvent($this->credits);
  }

  public function getTariffs()
  {
    return new Tariffs($this->credits);
  }
}

 ?>
