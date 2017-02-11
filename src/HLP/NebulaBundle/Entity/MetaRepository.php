<?php

/*
* Copyright 2014 HLP-Nebula authors, see NOTICE file
4
*
* Licensed under the EUPL, Version 1.1 or – as soon they
will be approved by the European Commission - subsequent
versions of the EUPL (the "Licence");
* You may not use this work except in compliance with the
Licence.
* You may obtain a copy of the Licence at:
*
*
http://ec.europa.eu/idabc/eupl
5
*
* Unless required by applicable law or agreed to in
writing, software distributed under the Licence is
distributed on an "AS IS" basis,
* WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either
express or implied.
* See the Licence for the specific language governing
permissions and limitations under the Licence.
*/ 

namespace HLP\NebulaBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * MetaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MetaRepository extends EntityRepository
{  
    public function getMetas($user, $page, $nbPerPage)
    {
        $query = $this->createQueryBuilder('m')
            ->leftJoin('m.users', 'u')
            ->where('u = :user')
            ->setParameter('user', $user)
            ->getQuery()
        ;

        $query
            ->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($query, true);
    }

    public function searchMetas($term)
    {
        $qb = $this->createQueryBuilder('m')
            ->where('m.metaId LIKE :keyword')
            ->orderBy('m.metaId', 'ASC')
            ->setParameter('keyword', '%'.$term.'%');

        return $qb->getQuery()->getResult();
    }

    public function incInstallCount($metaId)
    {
        $qb = $this->createQueryBuilder('m');
        return $qb->update()
            ->set('m.installCount', $qb->expr()->sum('m.installCount', '1'))
            ->where('m.metaId = :metaId')
            ->setParameter('metaId', $metaId)
            ->getQuery()
            ->execute();
    }
}
