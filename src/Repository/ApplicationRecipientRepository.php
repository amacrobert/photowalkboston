<?php

namespace App\Repository;

use App\Entity\ApplicationRecipient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApplicationRecipient>
 *
 * @method ApplicationRecipient|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationRecipient|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationRecipient[]    findAll()
 * @method ApplicationRecipient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRecipientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationRecipient::class);
    }

    public function save(ApplicationRecipient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ApplicationRecipient $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return ApplicationRecipient[]
    */
    public function findActive(): array
    {
        return $this->findBy(['active' => true]);
    }
}
