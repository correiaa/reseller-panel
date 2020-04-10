<?php

use App\Config;
use App\DatabaseCreditsInterface;

class DatabaseCredits implements DatabaseCreditsInterface
{
  private $type;
  private $user;
  private $name;
  private $pass;
  private $host;
  private $charset;

  private static $instance;

  public static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    $this->type = Config::get('db_type');
    $this->user = Config::get('db_user');
    $this->name = Config::get('db_name');
    $this->pass = Config::get('db_pass');
    $this->host = Config::get('db_host');
    $this->charset = Config::get('db_charset');
  }

  private function __clone() {}



  public function getType()
  {
    return $this->type;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getHost()
  {
    return $this->host;
  }

  public function getUser()
  {
    return $this->user;
  }

  public function getPass()
  {
    return $this->pass;
  }

  public function getCharset()
  {
    return $this->charset;
  }

}

 ?>
