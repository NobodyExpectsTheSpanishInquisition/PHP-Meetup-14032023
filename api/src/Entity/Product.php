<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private readonly int $id;

    #[ORM\Column(type: Types::GUID, nullable: false)]
    private readonly string $uuid;
    #[ORM\Column(length: 255, nullable: false)]
    private string $name;
    #[ORM\Column(type: Types::TEXT, nullable: false)]
    private string $description;
    #[ORM\Column(length: 20, nullable: false)]
    private string $price;

    #[ORM\ManyToOne(targetEntity: Discount::class)]
    #[ORM\JoinColumn(name: 'discount_id', referencedColumnName: 'id', nullable: true)]
    private ?Discount $discount;

    public function __construct(string $uuid, string $name, string $description, string $price)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;

        $this->discount = null;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function setPrice(string $price): void
    {
        $this->price = $price;
    }

    public function getDiscount(): ?Discount
    {
        return $this->discount;
    }

    public function setDiscount(?Discount $discount): void
    {
        $this->discount = $discount;
    }
}
