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
}
