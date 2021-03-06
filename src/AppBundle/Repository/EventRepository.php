<?php

namespace AppBundle\Repository;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends EntityRepository
{
    public function getByFilter(array $filter = [], $returnObject = true)
    {
        $builder = $this
            ->createQueryBuilder('p')
            ->orderBy('p.date', 'desc');

        if (isset($filter['author']) && $filter['author']) {
            $builder->andWhere('p.author = :author');
            $builder->setParameter('author', $filter['author']);
        }

        if (isset($filter['from']) && $filter['from'] && isset($filter['to']) && $filter['to']) {
            $builder->andWhere(
                $builder->expr()->between('p.date', ':date_from', ':date_to')
            );

            $builder->setParameter('date_from', $filter['from'], Type::DATE);
            $builder->setParameter('date_to', $filter['to'], Type::DATE);
        }

        if ($returnObject) {
            return $builder->getQuery()->getResult();
        } else {
            return $builder->getQuery()->getArrayResult();
        }
    }
}
