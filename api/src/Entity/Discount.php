<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiscountRepository::class)]
class Discount
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::GUID)]
    private string $uuid;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name;

    #[ORM\Column(type: Types::INTEGER)]
    private int $percentage;

    #[ORM\Column(type: Types::DATETIMETZ_IMMUTABLE)]
    private DateTimeImmutable $ends_at;

    public function __construct(string $uuid, string $name, int $percentage, DateTimeImmutable $ends_at)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->percentage = $percentage;
        $this->ends_at = $ends_at;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPercentage(): int
    {
        return $this->percentage;
    }

    public function setPercentage(int $percentage): void
    {
        $this->percentage = $percentage;
    }

    public function getEndsAt(): DateTimeImmutable
    {
        return $this->ends_at;
    }

    public function setEndsAt(DateTimeImmutable $ends_at): void
    {
        $this->ends_at = $ends_at;
    }
}
