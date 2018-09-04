<?php

namespace ProductBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{
    public function searchFind($str)
    {
        $qb = $this->createQueryBuilder('p')
                ->where('p.name LIKE :name')
                ->setParameter('name', '%'.$str.'%');
        return $qb->getQuery()->getResult();
    }

    public function findLack()
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.quantity <= 5');
        return $qb->getQuery()->getResult();
    }

    public function findLacknum()
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->where('p.quantity <= 5');
        return $qb->getQuery()->getSingleScalarResult();
    }
}
