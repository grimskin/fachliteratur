<?php


namespace App\Service;


use App\Entity\FbAuthor;
use App\Entity\FbBook;
use App\Repository\FbAuthorRepository;
use App\Repository\FbBookRepository;

class FlibustaManager
{
    private FlibustaClient $client;
    private FbAuthorRepository $authorRepository;
    private FbBookRepository $bookRepository;

    public function __construct(
        FlibustaClient $client,
        FbAuthorRepository $authorRepository,
        FbBookRepository $bookRepository
    ) {
        $this->client = $client;
        $this->authorRepository = $authorRepository;
        $this->bookRepository = $bookRepository;
    }

    public function fetchByAuthor(FbAuthor $author)
    {
        $this->client->fetch($author->getGuid());

        $books = $this->client->getBooks();

        foreach ($books as $bookData) {
            $book = FbBook::fromData($bookData);
            $book->setAuthorId($author->getId());
            $this->bookRepository->save($book);
        }
    }
}
