<?php

namespace App\Repository;

use App\Entity\FbAuthor;
use App\Entity\FbBook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FbBook|null find($id, $lockMode = null, $lockVersion = null)
 * @method FbBook|null findOneBy(array $criteria, array $orderBy = null)
 * @method FbBook[]    findAll()
 * @method FbBook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FbBookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FbBook::class);
    }

    public function save(FbBook $book): FbBook
    {
        $existingBook = $this->findOneBy(['guid' => $book->getGuid()]);

        if ($existingBook) return $existingBook;

        $em = $this->getEntityManager();
        $em->persist($book);
        $em->flush();

        return $book;
    }

    private function getLastWeekLimit(): \DateTimeInterface
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P1W'));

        return $date;
    }

    /**
     * @param FbAuthor $author
     *
     * @return FbBook[]
     */
    public function lastWeekByAuthor(FbAuthor $author): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pubDate >= :date')
            ->andWhere('b.authorId = :author')
            ->setParameter('date', $this->getLastWeekLimit())
            ->setParameter('author', $author->getId())
            ->orderBy('b.pubDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @param FbAuthor $author
     * @param int $limit
     *
     * @return FbBook[]
     */
    public function preWeekByAuthor(FbAuthor $author, int $limit = 1): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.pubDate < :date')
            ->andWhere('b.authorId = :author')
            ->setParameter('date', $this->getLastWeekLimit())
            ->setParameter('author', $author->getId())
            ->orderBy('b.pubDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
            ;
    }
}
