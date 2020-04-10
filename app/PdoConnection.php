<?php

namespace App;

use \PDO;
use App\Exceptions\PdoConnectionException;

class PdoConnection
{
  private $connection;
  private $db;
  private $options;
  private $dsn;

  public function __construct(DatabaseCreditsInterface $db)
  {
    $this->db['type'] = $db->getType();
    $this->db['host'] = $db->getHost();
    $this->db['name'] = $db->getName();
    $this->db['charset'] = $db->getCharset();
    $this->db['user'] = $db->getUser();
    $this->db['pass'] = $db->getPass();

    $this->dsn = "{$this->db['type']}:host={$this->db['host']};dbname={$this->db['name']};charset={$this->db['charset']}";

    $this->setConnectionOptions([
      PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_ASSOC
    ]);
  }

  public function setConnectionOptions(array $options)
  {
    $this->options = $options;
    $this->connection = null;
  }

  public function getConnection()
  {
    return (isset($this->connection)) ? $this->connection : $this->connect();
  }

  private function connect()
  {
    if(empty($this->connection))
    {
      try {
        $this->connection = new \PDO($this->dsn, $this->db['user'], $this->db['pass'], $this->options);
      } catch (\PDOException $e) {
        throw new PdoConnectionException($e->getMessage());
      }
    }
    return $this->connection;
  }
}


 ?>
