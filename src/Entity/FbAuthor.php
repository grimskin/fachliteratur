<?php

namespace App\Entity;

use App\Repository\FbAuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FbAuthorRepository::class)]
#[ORM\Table(name: 'fb_author')]
class FbAuthor
{
    #[ORM\Id()]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'bigint')]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(name: 'guid', type: 'string', length: 16)]
    private string $guid;

    #[ORM\Column(name: 'last_item_date', type: 'datetime')]
    private ?\DateTimeInterface $lastItemDate;

    #[ORM\Column(name: 'last_fetch', type: 'datetime')]
    private ?\DateTimeInterface $lastFetch;

    #[ORM\Column(name: 'created_at', type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getGuid(): string
    {
        return $this->guid;
    }

    public function setGuid(string $guid): void
    {
        $this->guid = $guid;
    }

    public function getLastItemDate(): ?\DateTimeInterface
    {
        return $this->lastItemDate;
    }

    public function setLastItemDate(?\DateTimeInterface $lastItemDate): self
    {
        $this->lastItemDate = $lastItemDate;

        return $this;
    }

    public function getLastFetch(): ?\DateTimeInterface
    {
        return $this->lastFetch;
    }

    public function setLastFetch(?\DateTimeInterface $lastFetch): self
    {
        $this->lastFetch = $lastFetch;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
