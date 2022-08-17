<?php
declare(strict_types=1);

namespace XYZ\Salaries\Domain\Enum;

enum SortingField: string
{
    case FIRST_NAME = 'first_name';
    case LAST_NAME = 'last_name';
    case DEPARTMENT = 'department';
    case BASE_SALARY = 'base_salary';
    case TOTAL_SALARY = 'total_salary';
    case SALARY_SUPPLEMENT_AMOUNT = 'salary_supplement_amount';
    case SALARY_SUPPLEMENT_TYPE = 'salary_supplement_type';
}
