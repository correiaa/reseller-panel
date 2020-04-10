<?php

namespace App;

use \ReflectionClass;
use \ReflectionMethod;
use Exceptions\ModelException;

abstract class Model
{
  protected $data;

  public function __construct(array $fields)
  {
    $this->data = array_fill_keys($fields, ['value' => '', 'filter' => FILTER_DEFAULT]);
  }

  final public function __call($method, $args = null)
  {
    if(preg_match('~^(get|set)~', $method, $prefix))
    {
      $snakeCaseKey = strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $method));
      $key = preg_replace('~^(get|set)_~', '', $snakeCaseKey);
      switch($prefix[0])
      {
        case 'get':
          return $this->get($key);
        case 'set':
          return $this->set($key, $args);
      }
    }
    throw new ModelException("Model method $method does not exist", 1);
  }

  protected function set($key, $value)
  {
    $value = is_array($value) ? current($value) : $value;

    if(array_key_exists($key, $this->data))
    {
      $this->data[$key]['value'] = filter_var($value, $this->data[$key]['filter']);
    }
  }

  protected function get($key)
  {
    return array_key_exists($key, $this->data) ? $this->data[$key]['value'] : null;
  }
}


 ?>
