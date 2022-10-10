<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity('name')]
class County implements Stringable
{
    use IdTrait;
    use TimestampTrait;

    public const ABROAD = 'abroad';
    public const MULTIPLE = 'multiple';

    #[ORM\Column(options: ['collation' => 'utf8mb4_0900_as_ci'])]
    #[Assert\NotBlank]
    private string $name = '';

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $systemName = null;

    #[ORM\Column(type: Types::INTEGER, unique: true, nullable: true)]
    private ?int $externalId = null;

    /**
     * @var Collection<Settlement>
     */
    #[ORM\OneToMany(targetEntity: Settlement::class, mappedBy: 'county', orphanRemoval: true)]
    private Collection $settlements;

    public function __construct()
    {
        $this->settlements = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(?string $systemName): self
    {
        $this->systemName = $systemName;

        return $this;
    }

    public function getExternalId(): ?int
    {
        return $this->externalId;
    }

    public function setExternalId(?int $externalId): County
    {
        $this->externalId = $externalId;

        return $this;
    }

    /**
     * @return Collection<Settlement>
     */
    public function getSettlements(): Collection
    {
        return $this->settlements;
    }

    public function addSettlement(Settlement $settlement): self
    {
        if (!$this->settlements->contains($settlement)) {
            $this->settlements[] = $settlement;
            $settlement->setCounty($this);
        }

        return $this;
    }

    public function removeSettlement(Settlement $settlement): self
    {
        if ($this->settlements->removeElement($settlement)) {
            // set the owning side to null (unless already changed)
            if ($settlement->getCounty() === $this) {
                $settlement->setCounty(null);
            }
        }

        return $this;
    }
}
