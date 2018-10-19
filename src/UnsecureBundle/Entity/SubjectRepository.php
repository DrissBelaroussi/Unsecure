<?php

namespace UnsecureBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * SubjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SubjectRepository extends EntityRepository
{
    public function recentSubject($quantity = 1)
    {
        $query = $this->createQueryBuilder('s')
                ->addSelect('u')
                ->innerJoin('s.user', 'u')
                ->where('s.private = 0')
                ->orderBy('s.creationDate', 'DESC')
                ->setMaxResults($quantity)
                ->getQuery();

        $subjects = $query->getResult();
        return $subjects;
    }

    public function mySubjects($iCurrentUserId)
    {
        $query = $this->createQueryBuilder('s')
        ->addSelect('u')
        ->innerJoin('s.user', 'u')
        ->where('u.id = ' . $iCurrentUserId)
        ->orderBy('s.creationDate', 'DESC')
        ->getQuery();
    
        $subjects = $query->getResult();
        return $subjects;
    }
    
    /**
     * Retrieve a single fullfilled subject
     * (with user and comments)
     *
     * @param int $subjectId
     *
     * @return Subject
     */
    public function findFullOne($subjectId)
    {
        $sql = "SELECT s0_.text AS text, s0_.creationDate AS creationDate, s0_.private AS private, u1_.firstName AS firstName, u1_.lastName AS lastName, s0_.user AS user, c2_.userId AS userId24, c2_.subjectId AS subjectId25 FROM subject s0_ INNER JOIN user u1_ ON s0_.user = u1_.id LEFT JOIN comment c2_ ON s0_.id = c2_.subjectId LEFT JOIN user u3_ ON c2_.userId = u3_.id WHERE s0_.id = " . $subjectId ." ORDER BY c2_.creationDate DESC";
            
        try {
            return $this->_em->getConnection()->fetchAll($sql)[0];
        } catch (\Exception $e) {
            echo $e->getMessage(); // Debug
            exit;
        }
        ;
    }
    
    public function findFull($subjectId)
    {
        return $this->createQueryBuilder('s')
        ->addSelect('u')
        ->addSelect('c')
        ->addSelect('cu')
        ->innerJoin('s.user', 'u')
        ->leftJoin('s.comments', 'c')
        ->leftJoin('c.user', 'cu')
        ->where('s.id = :subjectId')
        ->setParameters(array(
            ':subjectId' => $subjectId,
        ))
        ->getQuery()
        ->getOneOrNullResult();
    }
}
