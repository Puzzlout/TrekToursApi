<?php

namespace ApiBundle\Repository;

use Doctrine\ORM\EntityRepository;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CustomerInfoRequestRepository
 */
class CustomerInfoRequest extends EntityRepository
{

    public function findAllWithFilters($offset, $limit, $createdFrom = null, $createdTo = null)
    {
        $queryBuilder = $this->createQueryBuilder('cir');
        if(!is_null($createdFrom))
        {
            $queryBuilder = $queryBuilder->andWhere('cir.created >= :from')->setParameter('from', $createdFrom);
        }
        if(!is_null($createdTo))
        {
            $queryBuilder = $queryBuilder->andWhere('cir.created <= :to')->setParameter('to', $createdTo);
        }
        $query = $queryBuilder->setMaxResults($limit)->setFirstResult($offset)->orderBy('cir.created','DESC')
            ->getQuery();
        $paginator = new Paginator($query, false);
        $count = count($paginator);
        $customerInfoRequests = $query->getResult();

        return [
            "items" => $customerInfoRequests,
            "totalCount" => $count
        ];
    }
}