<?php
declare(strict_types=1);

namespace XYZ\SharedKernel\Infrastructure;

use XYZ\SharedKernel\Domain\Clock;

final class SystemClock implements Clock
{
    public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
