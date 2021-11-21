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
            ->getQuery()
            ->getResult()
            ;
    }

    public function addAuthor($guid, $authorName): FbAuthor
    {
        $existingRecord = $this->findOneBy(['guid' => $guid]);

        if ($existingRecord) return $existingRecord;

        $record = new FbAuthor();
        $record->setGuid($guid);
        $record->setName($authorName);

        try {
            $this->getEntityManager()->persist($record);
            $this->getEntityManager()->flush();
        } catch (\Exception $e) {}

        return $record;
    }
}
