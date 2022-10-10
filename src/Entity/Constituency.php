<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Stringable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[UniqueEntity('name')]
class Constituency implements Stringable
{
    use IdTrait;
    use TimestampTrait;

    #[ORM\Column(options: ['collation' => 'utf8mb4_0900_as_ci'])]
    #[Assert\NotBlank]
    private string $name = '';

    /**
     * @var Collection<Settlement>
     */
    #[ORM\ManyToMany(targetEntity: Settlement::class, mappedBy: 'constituencies')]
    private Collection $settlements;

    #[ORM\Column(length: 4, options: ['collation' => 'utf8mb4_0900_as_ci'])]
    private string $externalId = '';

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
            $this->settlements->add($settlement);
        }

        return $this;
    }

    public function removeSettlement(Settlement $settlement): self
    {
        $this->settlements->removeElement($settlement);

        return $this;
    }

    public function setExternalId(string $externalId): self
    {
        $this->externalId = $externalId;

        return $this;
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }
}
