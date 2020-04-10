<?php

class PortalTariffPlan
{
  private static $instance = null;

  private $all;

  private $shortKeys;

  private function __construct()
  {
    $this->all = PortalApiResources::getInstance()->getTariffs()->select();
    $this->shortKeys = ['name', 'external_id', 'user_default'];
  }

  private function __clone() {}

  public static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function getAll($onlyExternalId = false)
  {
    $tariffs = $this->all;
    foreach($tariffs as &$t)
    {
      $t = ($onlyExternalId === false) ? $this->getShort($t) : $t['external_id'];
    }
    return $tariffs;
  }

  private function getShort(array $tariff)
  {
    return array_filter($tariff, function($key){
      return in_array($key, $this->shortKeys);
    }, ARRAY_FILTER_USE_KEY);
  }

  public function getDefault($onlyExternalId = false)
  {
    foreach($this->all as $t)
    {
      if($t['user_default'] == true)
      {
        return ($onlyExternalId === false) ? $this->getShort($t) : $t['external_id'];
      }
    }
  }
}


 ?>
