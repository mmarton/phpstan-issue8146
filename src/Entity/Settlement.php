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
class Settlement implements Stringable
{
    use IdTrait;
    use TimestampTrait;

    #[ORM\Column(options: ['collation' => 'utf8mb4_0900_as_ci'])]
    #[Assert\NotBlank]
    private string $name = '';

    #[ORM\ManyToOne(targetEntity: County::class, inversedBy: 'settlements')]
    private ?County $county = null;

    /**
     * @var Collection<Constituency>
     */
    #[ORM\ManyToMany(targetEntity: Constituency::class, inversedBy: 'settlements')]
    private Collection $constituencies;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $longitude = null;

    public function __construct()
    {
        $this->constituencies = new ArrayCollection();
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

    public function getCounty(): ?County
    {
        return $this->county;
    }

    public function setCounty(?County $county): self
    {
        $this->county = $county;

        return $this;
    }

    /**
     * @return Collection<Constituency>
     */
    public function getConstituencies(): Collection
    {
        return $this->constituencies;
    }

    public function addConstituency(Constituency $constituency): self
    {
        if (!$this->constituencies->contains($constituency)) {
            $this->constituencies->add($constituency);
        }

        return $this;
    }

    public function removeConstituency(Constituency $constituency): self
    {
        $this->constituencies->removeElement($constituency);

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
