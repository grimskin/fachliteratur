<?php

namespace App\Entity;

use App\Repository\FbBookRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FbBookRepository::class)]
#[ORM\Table(name: 'fb_book')]
class FbBook
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'bigint')]
    private int $id;

    #[ORM\Column(name: 'author_id', type: 'bigint')]
    private int $authorId;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(name: 'guid', type: 'string', length: 16)]
    private string $guid;

    #[ORM\Column(name: 'description', type: 'string', length: 2048)]
    private string $description;

    #[ORM\Column(name: 'pub_date', type: 'datetime')]
    private \DateTimeInterface $pubDate;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $authorId): void
    {
        $this->authorId = $authorId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): void
    {
        $this->guid = $guid;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPubDate(): \DateTimeInterface
    {
        return $this->pubDate;
    }

    public function setPubDate(\DateTimeInterface $pubDate): void
    {
        $this->pubDate = $pubDate;
    }

    public function getCreatedAt(): \DateTime|\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime|\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public static function fromData(array $data): self
    {
        $result = new FbBook();

        $guidArr = explode('/', $data['guid']);
        $guid = $guidArr[count($guidArr)-1];

        // Temporary disabled description saving because of issues with Unicode string being too long
//        $description = $data['description'] ?: '';
//        if (!is_string($description)) $description = '';
//        if (strlen($description) > 2000) $description = substr($description, 0, 2000) . '...';
        $description = '';

        $result->setName($data['title']);
        $result->setDescription(strip_tags(trim($description)));
        $result->setGuid($guid);
        $result->setPubDate(new \DateTime($data['pubDate']));

        return $result;
    }
}
