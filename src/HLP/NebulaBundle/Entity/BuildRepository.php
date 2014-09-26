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

/**
 * BuildRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BuildRepository extends EntityRepository
{
  public function findSingleBuild($ownerNameCanonical, $modId, $branchId, $versionMajor = null, $versionMinor = null, $versionPatch = null, $versionPreRelease = null, $versionMetadata = null)
  {
    $qb = $this->_em->createQueryBuilder();
    $qb->select('u')
       ->from('HLPNebulaBundle:Build', 'u')
       ->leftJoin('u.branch', 'b')
       ->addSelect('b')
       ->leftJoin('b.mod', 'm')
       ->addSelect('m')
       ->leftJoin('m.userAsOwner', 'uo')
       ->addSelect('uo')
       ->leftJoin('m.teamAsOwner', 'to')
       ->addSelect('to')
       ->leftJoin('u.packages', 'p')
       ->addSelect('p')
       ->leftJoin('u.actions', 'a')
       ->addSelect('a')
       ->leftJoin('p.envVars', 'e')
       ->addSelect('e')
       ->leftJoin('p.dependencies', 'd')
       ->addSelect('d')
       ->leftJoin('p.files', 'f')
       ->addSelect('f')
       ->where('uo.usernameCanonical = :nameCanonical OR to.nameCanonical = :nameCanonical')
       ->andWhere('m.modId = :modId')
       ->andWhere('b.branchId = :branchId')
       ->setParameter('nameCanonical', $ownerNameCanonical)
       ->setParameter('modId', $modId)
       ->setParameter('branchId', $branchId);
       
    if(isset($versionMajor) && isset($versionMinor) && isset($versionPatch))
    {
      $qb->andWhere('u.versionMajor = :versionMajor')
         ->andWhere('u.versionMinor = :versionMinor')
         ->andWhere('u.versionPatch = :versionPatch')
         ->setParameter('versionMajor', (int) $versionMajor)
         ->setParameter('versionMinor', (int) $versionMinor)
         ->setParameter('versionPatch', (int) $versionPatch);
         
      if(isset($versionPreRelease))
      {
        $qb->andWhere('u.versionPreRelease = :versionPreRelease')
           ->setParameter('versionPreRelease', $versionPreRelease);
      }
      else
      {
        $qb->andWhere($qb->expr()->isNull('u.versionPreRelease'));
      }

      if(isset($versionMetadata))
      {
        $qb->andWhere('u.versionMetadata = :versionMetadata')
           ->setParameter('versionMetadata', $versionMetadata);
      }
      else
      {
        $qb->andWhere($qb->expr()->isNull('u.versionMetadata'));
      }
    }
    else
    {
      $qb->orderBy('u.updated', 'DESC')
         ->setMaxResults(1);
    }

    return $qb->getQuery()
              ->getOneOrNullResult();
  }
}
