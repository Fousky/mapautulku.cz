<?php declare(strict_types = 1);

namespace App\Repository\User;

use App\Entity\User\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

/**
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
final class UserRepository extends EntityRepository
{
    public function __construct(EntityManager $em)
    {
        parent::__construct($em, $em->getClassMetadata(User::class));
    }

    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush($user);
    }
}
