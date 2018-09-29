<?php declare(strict_types = 1);

namespace App\Model\Security;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class UserProvider implements UserProviderInterface
{
    /** @var UserRepository */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username): User
    {
        $user = $this->userRepository->findByEmail((string) $username);

        if ($user === null) {
            throw new UsernameNotFoundException(sprintf('Username `%s` not found.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!($user instanceof User)) {
            throw new UnsupportedUserException(sprintf(
                'User class %s is not supported in %s.',
                get_class($user),
                __METHOD__
            ));
        }

        return $this->loadUserByUsername($user->getEmail());
    }

    public function supportsClass($class): bool
    {
        if (!class_exists($class)) {
            throw new \RuntimeException(sprintf(
                'Class `%s` does not exists!',
                $class
            ));
        }

        return $class === User::class ||
               is_subclass_of($class, User::class);
    }
}
