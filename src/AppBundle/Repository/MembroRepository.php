<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class MembroRepository extends EntityRepository {
    
    public function countByCatreBloco(string $bloco) {
        $rsm = new \Doctrine\ORM\Query\ResultSetMapping;
        $rsm->addScalarResult('inscritos', 'inscritos', 'integer');
        return $this->getEntityManager()->createNativeQuery(
                'SELECT count(*) inscritos FROM membro WHERE estadia = :bloco', 
                $rsm)
                ->setParameter(':bloco', $bloco)
                ->getSingleScalarResult();
    }
    
}
