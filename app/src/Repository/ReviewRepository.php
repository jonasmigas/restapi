<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function findDifferentUsersWithCompanyId(int $companyId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT distinct r.user
            FROM App\Entity\Review r
            WHERE r.company = :companyId
            ORDER BY r.user ASC'
        )->setParameter('companyId', $companyId);

        return $query->getArrayResult();
    }

    /**
     * @return Review[]
     */
    public function findDifferentCompaniesWithUsers($users, $companyId): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r.company, c.name
            FROM App\Entity\Review r
            INNER JOIN App\Entity\Company c  
            WHERE r.user in (:users) and r.company = c.company_id and c.company_id <> :company
            GROUP BY r.company
            ORDER BY r.company ASC'
        )->setParameters([
            'users' => $users,
            'company' => $companyId
        ]);

        return $query->getArrayResult();
    }
}
