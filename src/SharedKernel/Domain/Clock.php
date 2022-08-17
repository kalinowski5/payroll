<?php
declare(strict_types=1);

namespace XYZ\SharedKernel\Domain;

interface Clock
{
    public function now(): \DateTimeImmutable;
}
