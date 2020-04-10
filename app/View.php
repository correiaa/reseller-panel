<?php

namespace App;

use App\Exceptions\ViewException;

class View
{
  private $views;
  private $master;
  private $useMaster;

  public function __construct()
  {
      $this->views = __DIR__ . '/Views/';
      $this->master = $this->views.'master.template.php';
      $this->useMaster(true);
  }

  public function useMaster($use)
  {
    $this->useMaster = (boolean)$use;
  }

  public function generate($content, $data = [])
  {
    if(!file_exists($this->master) && $this->useMaster == true)
    {
      throw new ViewException("Unable to render the page: $this->master does not exist");
    }

    $content = $this->views . "view.$content.php";

    if(!file_exists($content))
    {
      throw new ViewException("Unable to render the page: $content view does not exist");
    }

    if(!empty($data))
    {
        extract($data);
    }

    ($this->useMaster == true) ? include $this->master: include $content;
  }

  public function generateJson($data, $error = null)
  {
    $response = [
      'status' => '',
      'results' => '',
      'error' => ''
    ];

    if($error !== null)
    {
      $response['status'] = 'ERROR';
      $response['error'] = $error;
    }
    else
    {
      $response['status'] = 'OK';
      $response['results'] = $data;
    }

    echo json_encode($response);
    exit;
  }

  public function echoJson($data)
  {
    echo json_encode($data);
    exit;
  }
}

 ?>
