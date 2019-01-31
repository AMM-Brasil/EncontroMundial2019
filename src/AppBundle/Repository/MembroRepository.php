<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MembroRepository extends EntityRepository
{
    public function countByCatreBloco($bloco)
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('inscritos', 'inscritos', 'integer');

        return $this->getEntityManager()->createNativeQuery(
                ' SELECT count(m.id) as inscritos FROM membro m '.
                ' JOIN inscricao i ON i.id = m.inscricao_id '.
                ' WHERE m.estadia = :bloco '.
                ' AND i.deposito_identificado = 1 ',
                $rsm)
                ->setParameter(':bloco', $bloco)
                ->getSingleScalarResult();
    }

    public function countByAll()
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('inscritos', 'inscritos', 'integer');

        return $this->getEntityManager()->createNativeQuery(
                ' SELECT count(m.id) as inscritos FROM membro m ',
                $rsm)
                ->getSingleScalarResult();
    }

    public function countByPago()
    {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping();
        $rsm->addScalarResult('inscritos', 'inscritos', 'integer');

        return $this->getEntityManager()->createNativeQuery(
                ' SELECT count(m.id) as inscritos FROM membro m '.
                ' JOIN inscricao i ON i.id = m.inscricao_id '.
                ' AND i.deposito_identificado = 1 ',
                $rsm)
                ->getSingleScalarResult();
    }
}
