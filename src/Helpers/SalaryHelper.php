<?php

namespace App\Helpers;

class SalaryHelper
{
    /**
     * Calculates the total salary from an array of salary components.
     *
     * @param array $employeeData Associative array containing salary components like 'basic_salary', 'travel_tickets', etc.
     * @return float The calculated total salary.
     */
    public static function calculateTotalSalary(array $employeeData): float
    {
        $basic = (float)($employeeData['basic_salary'] ?? 0);
        $travel = (float)($employeeData['travel_tickets'] ?? 0);
        $oil = (float)($employeeData['oil_cost'] ?? 0);
        $housing = (float)($employeeData['housing_cost'] ?? 0);
        $living = (float)($employeeData['living_cost'] ?? 0);

        return $basic + $travel + $oil + $housing + $living;
    }
}
