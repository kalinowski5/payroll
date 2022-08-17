<?php
declare(strict_types=1);


namespace XYZ\Salaries\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\Repository\EmployeeRepository;

/**
 * @template-extends ServiceEntityRepository<Employee>
 */
final class EmployeeORMRepository  extends ServiceEntityRepository implements EmployeeRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function findAll(): array
    {
        return $this->findBy([]);
    }
}
