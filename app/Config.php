<?php

namespace App;

use \Exception;


class Config
{
  private static $instance = null;

  private $params = null;

  private function __construct()
  {
    if($this->params === null)
    {
      $this->initParams();
    }
  }

  private function __clone() {}

  private function initParams()
  {
    $config = __DIR__.'/../config/config.ini';
    if(!is_readable($config))
    {
      throw new Exception("Cannot read $file");
    }
    $this->params = parse_ini_file($config);

    $custom = __DIR__.'/../config/custom.ini';
    if(is_readable($custom))
    {
      $this->params = array_merge($this->params, parse_ini_file($custom));
    }

    return $this->params;
  }

  public static function get($param, $default = null)
  {
    $config = self::getInstance();
    $params = $config->params;
    if(isset($params[$param]))
    {
      return $params[$param];
    }
    elseif ($default !== null)
    {
      return $default;
    }
    throw new Exception("$param not defined");
  }

  private static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

}

 ?>
