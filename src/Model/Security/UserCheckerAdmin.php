<?php declare(strict_types = 1);

namespace App\Model\Security;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserCheckerAdmin implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            throw new \RuntimeException(sprintf(
                'Class %s is not supported, only %s',
                get_class($user),
                User::class
            ));
        }

        if ($user->isEnabled() === false) {
            throw new DisabledException(sprintf(
                'User e-mail `%s` is disabled.',
                $user->getEmail()
            ));
        }
    }
}
