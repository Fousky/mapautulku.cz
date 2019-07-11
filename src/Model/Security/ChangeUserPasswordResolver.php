<?php declare(strict_types = 1);

namespace App\Model\Security;

use App\Entity\User\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ChangeUserPasswordResolver
{
    /** @var EncoderFactoryInterface */
    protected $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function resolvePlainPassword(User $user): void
    {
        $plain = $user->getPlainPassword();

        if ($plain === null) {
            return;
        }

        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($plain, $user->getSalt());

        $user->setPassword($encodedPassword);
        $user->eraseCredentials();
    }
}
