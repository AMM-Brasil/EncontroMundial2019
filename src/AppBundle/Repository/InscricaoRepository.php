<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class InscricaoRepository extends EntityRepository
{
    public function findByAtrasados()
    {
        return $this->getEntityManager()->createQueryBuilder()
        ->select('i')
        ->from('AppBundle:Inscricao', 'i')
        ->leftJoin('i.comprovantes', 'c')
        ->where('c.id is null')
        ->andWhere('i.dataLimitePagamento < :data')
        ->andWhere('i.depositoIdentificado = :deposito')
        ->setParameter('data', new \DateTime())
        ->setParameter('deposito', false)
        ->getQuery()->getResult();
    }
}
