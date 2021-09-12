<?php


namespace App\View;


use App\Entity\FbAuthor;

class FbAuthorView
{
    public string $name = '';
    public string $guid = '';

    public static function fromEntity(FbAuthor $author): FbAuthorView
    {
        $result = new self();

        $result->name = $author->getName();
        $result->guid = $author->getGuid();

        return $result;
    }
}
