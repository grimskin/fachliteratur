<?php


namespace App\DTO;


use App\Entity\FbBook;

class BookDTO
{
    public string $guid;
    public string $name;
    public string $pubDate;
    public string $canonicalDate;

    public static function fromEntity(FbBook $book): self
    {
        $result = new self();

        $result->guid = $book->getGuid();

        $result->formatName($book->getName());
        $result->formatPubDate($book->getPubDate());

        return $result;
    }

    private function formatName(string $name)
    {
        $nameArr = explode(' - ', $name, 3);

        if (isset($nameArr[1])) {
            $this->name = trim($nameArr[0]) . ', ' . trim($nameArr[1]);
        } else {
            $this->name = $name;
        }
    }

    private function formatPubDate(\DateTimeInterface $pubDate)
    {
        $this->canonicalDate = $pubDate->format('Y-m-d');
        $this->pubDate = $pubDate->format('j M y');
    }
}
