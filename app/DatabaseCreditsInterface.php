<?php

namespace App;

interface DatabaseCreditsInterface
{
  public function getName();
  public function getHost();
  public function getUser();
  public function getPass();
  public function getCharset();
  public function getType();
}

 ?>
