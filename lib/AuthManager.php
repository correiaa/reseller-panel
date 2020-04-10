<?php

use App\Mappers\UserMapper;

class AuthManager
{
  private static $instance;
  private $userMapper;

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
    $this->userMapper = new UserMapper(DatabaseCredits::getInstance());
  }

  private function __clone() {}

  public function login()
  {
    $filters = $this->getFilters();
    $data = filter_input_array(INPUT_POST, $filters);
    if(empty($data['login']) || empty($data['password']))
    {
      $this->logout("Entered data: [login => {$_POST['login']}, password => {$_POST['login']}]");
    }

    $this->userMapper->setFindId('login');
    $user = $this->userMapper->find($data['login']);
    if(empty($user))
    {
      $this->logout("Cannot find a user with login [{$data['login']}]");
    }

    if(password_verify($data['password'], $user->getPassword()) !== true)
    {
      $this->logout("Login [{$data['login']}]: password does not match]");
    }

    if($user->getStatus() != true)
    {
      $this->logout("Reseller [login => {$data['login']}, status => {$data['status']}] is switched off");
    }

    $_SESSION['user_id'] = $user->getId();
    $user->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
    $user->setLastLogin(date("Y-m-d H:i:s"));
    $this->userMapper->update($user);
  }

  public function logout($message = 'Authorization failed.')
  {
    if(isset($_SESSION['user_id']))
    {
      $_SESSION['user_id'] = null;
    }

    throw new App\Exceptions\AuthException($message, 1);
  }

  public function isAuthorized()
  {
    return isset($_SESSION['user_id']);
  }

  public function getSessionId()
  {
    if($this->isAuthorized() != true)
    {
      $this->logout();
    }
    return $_SESSION['user_id'];
  }

  private function getFilters()
  {
    return [
      'login'           => FILTER_SANITIZE_STRING,
      'password'        => FILTER_SANITIZE_STRING,
    ];
  }
}


 ?>
