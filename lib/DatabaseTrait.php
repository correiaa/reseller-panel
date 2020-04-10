<?php

trait DatabaseTrait
{
  public function getPdoConnection()
  {
    $creds = DatabaseCredits::getInstance();

    $dsn = $creds->getType().':dbname='.$creds->getName().';host='.$creds->getHost();
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    return new PDO($dsn, $creds->getUser(), $creds->getPass());
  }
}

 ?>
