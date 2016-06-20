<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * CustomerInfoRequestRepository
 */
class CustomerInfoRequest extends EntityRepository
{

    public function findAllWithFilters($offset, $limit, $from = null, $to = null)
    {
        $queryBuilder = $this->createQueryBuilder('cir');
        if(!is_null($from))
        {
            $queryBuilder = $queryBuilder->where('cir.created >= :from')->setParameter('from', $from);
        }
        if(!is_null($to))
        {
            $queryBuilder = $queryBuilder->where('cir.created <= :to')->setParameter('to', $to);
        }
        $query = $queryBuilder->setMaxResults($limit)->setFirstResult($offset)->getQuery();
        $customerInfoRequests = $query->getResult();

        return $customerInfoRequests;
    }
}