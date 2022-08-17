<?php
declare(strict_types=1);

namespace XYZ\SharedKernel\Infrastructure;

use XYZ\SharedKernel\Domain\Clock;

final class FakeClock implements Clock
{
    private \DateTimeImmutable $now;

    public function __construct(?\DateTimeImmutable $now = null)
    {
        $this->now = $now ?? new \DateTimeImmutable('1990-01-01');
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }

    public function givenCurrentDateTimeIs(\DateTimeImmutable $dateTime): void
    {
        $this->now = $dateTime;
    }
}
