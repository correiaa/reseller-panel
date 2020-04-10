<?php

use App\Mappers\UserMapper;
use App\Models\UserModel;

class CurrentUser
{
  private static $instance;
  private $userMapper;
  private $profile;

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
    $user = $this->userMapper->find(AuthManager::getInstance()->getSessionId());
    $this->setProfile($user);
  }

  private function setProfile(UserModel $userModel)
  {
    $this->profile = [
      'id'         => $userModel->getId(),
      'name'       => $userModel->getName(),
      'login'      => $userModel->getLogin(),
      'password'   => $userModel->getPassword(),
      'parent_id'  => $userModel->getParentId(),
      'balance'    => $userModel->getBalance(),
      'role'       => $userModel->getRole(),
      'last_login' => $userModel->getLastLogin(),
    ];
  }

  public function isAdmin()
  {
    return $this->profile['role'] === 'admin';
  }

  public function getProfile()
  {
    return $this->profile;
  }

  public function getId()
  {
    return $this->profile['id'];
  }

  private function __clone() {}
}

 ?>
