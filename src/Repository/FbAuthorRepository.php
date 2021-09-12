<?php

namespace App\Repository;

use App\Entity\FbAuthor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FbAuthor|null find($id, $lockMode = null, $lockVersion = null)
 * @method FbAuthor|null findOneBy(array $criteria, array $orderBy = null)
 * @method FbAuthor[]    findAll()
 * @method FbAuthor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FbAuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FbAuthor::class);
    }

    /**
     * @return FbAuthor[]
     */
    public function allAuthors(): array
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function addAuthor($guid, $authorName)
    {
        $existingRecord = $this->findOneBy(['guid' => $guid]);

        if ($existingRecord) return;

        $record = new FbAuthor();
        $record->setGuid($guid);
        $record->setName($authorName);

        try {
            $this->getEntityManager()->persist($record);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {}
    }


    // /**
    //  * @return Author[] Returns an array of Author objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Author
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
