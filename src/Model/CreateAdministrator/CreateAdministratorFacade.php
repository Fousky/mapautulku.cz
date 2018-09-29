<?php declare(strict_types = 1);

namespace App\Model\CreateAdministrator;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class CreateAdministratorFacade
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    /** @var EntityManagerInterface */
    private $manager;

    /** @var UserRepository */
    private $userRepository;

    /** @var ValidatorInterface */
    private $validator;

    public function __construct(
        EncoderFactoryInterface $encoderFactory,
        EntityManagerInterface $manager,
        UserRepository $userRepository,
        ValidatorInterface $validator
    ) {
        $this->encoderFactory = $encoderFactory;
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function createUser(SymfonyStyle $io): void
    {
        $user = new User();

        $user
            ->setEmail($this->askEmail($io))
            ->setPassword(
                $this
                    ->encoderFactory
                    ->getEncoder($user)
                    ->encodePassword(
                        $this->askPassword($io),
                        ''
                    )
            )
            ->setRoles($this->askRoles($io));

        $this->manager->persist($user);
        $this->manager->flush();
    }

    private function askEmail(SymfonyStyle $io): string
    {
        return $io->ask('E-mail', null, [$this, 'validateEmail']);
    }

    private function askPassword(SymfonyStyle $io): string
    {
        return $io->askHidden('Password', [$this, 'validatePassword']);
    }

    private function askRoles(SymfonyStyle $io): array
    {
        return (array) $io->choice('Roles', [
            'ROLE_USER',
            'ROLE_ADMIN',
            'ROLE_SUPER_ADMIN',
        ]);
    }

    public function validateEmail(?string $value): string
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Email([
                'checkHost' => true,
                'strict' => true,
            ]),
        ];

        if ($this->validator->validate($value, $constraints)->count() > 0) {
            throw new \RuntimeException('Enter valid e-mail.');
        }

        $alreadyHasUser = $this->userRepository->findByEmail((string) $value) !== null;

        if ($alreadyHasUser) {
            throw new \RuntimeException('This user already exists.');
        }

        return (string) $value;
    }

    public function validatePassword(?string $value): string
    {
        $constraints = [
            new Assert\NotBlank(),
            new Assert\Length([
                'min' => User::PASSWORD_LENGTH_MIN,
                'max' => User::PASSWORD_LENGTH_MAX,
            ]),
        ];

        /** @var \Symfony\Component\Validator\ConstraintViolationList $violationsList */
        $violationsList = $this->validator->validate($value, $constraints);

        if ($violationsList->count() > 0) {
            $messages = [];
            foreach ($violationsList as $item) {
                $messages[] = $item->getMessage();
            }

            throw new \RuntimeException(sprintf(
                'Password errors: %s',
                implode(PHP_EOL, $messages)
            ));
        }

        return (string) $value;
    }
}
