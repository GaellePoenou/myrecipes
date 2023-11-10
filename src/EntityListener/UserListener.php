<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserListener
{
  private UserPasswordHasherInterface $passwordHasher;

  public function __construct(UserPasswordHasherInterface $passwordHasher)
  {
    $this->passwordHasher = $passwordHasher;
  }

  public function userListener($args)
  {
    // Symfony seems to expect this method, but it can be empty.
  }

  public function prePersist(User $user)
  {
    $this->encodePassword($user);
  }

  public function preUpdate(User $user)
  {
    $this->encodePassword($user);
  }

  /**
   * This method encodes the password based on the plainPassword.
   *
   * @param User $user
   */
  public function encodePassword(User $user)
  {
    if ($user->getPlainPassword() === null) {
      return;
    }

    $user->setPassword(
      $this->passwordHasher->hashPassword(
        $user,
        $user->getPlainPassword()
      )
    );
    $user->setPlainPassword(null);
  }
}
