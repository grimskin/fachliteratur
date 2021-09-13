<?php


namespace App\DTO;


use App\Entity\FbBook;

class BookDTO
{
    public string $guid;
    public string $name;
    public string $pubDate;

    public static function fromEntity(FbBook $book): self
    {
        $result = new self();

        $result->guid = $book->getGuid();
        $result->name = $book->getName();
        $result->pubDate = $book->getPubDate()->format('Y-m-d');

        return $result;
    }
}
