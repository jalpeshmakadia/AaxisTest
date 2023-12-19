<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'product')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['sku'], message: 'This sku is already in use.')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id;

    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    #[Assert\Unique]
    #[ORM\Column(type: Types::STRING, length: 50, unique: true)]
    private ?string $sku;

    #[Assert\NotBlank]
    #[Assert\Length(max: 250)]
    #[ORM\Column(type: Types::STRING, length: 250)]
    private ?string $productName;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    private ?\DateTime $updateAt;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSku(): ?string
    {
        return $this->sku;
    }

    public function setSku(?string $sku): void
    {
        $this->sku = $sku;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(?string $productName): void
    {
        $this->productName = $productName;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(?\DateTime $createdAt): void
    {
        $this->createdAt = (empty($createdAt)) ? new \DateTime('now') : $createdAt;
    }

    public function getUpdateAt(): ?\DateTime
    {
        return $this->updateAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdateAt(?\DateTime $updateAt): void
    {
        $this->updateAt = (empty($updateAt)) ? new \DateTime('now') : $updateAt;;
    }

}
