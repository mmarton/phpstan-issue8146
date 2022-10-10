<?php

declare(strict_types=1);

namespace App\Entity\Traits;

trait TimestampTrait
{
    use CreatedTrait;
    use UpdatedTrait;

    public function isUpdated(): bool
    {
        return $this->createdAt->getTimestamp() !== $this->updatedAt->getTimestamp();
    }
}
