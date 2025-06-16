<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Payroll;
use Carbon\Carbon;

class PayrollService
{
    public function generateForMonth(int $year, int $month): void
    {
        $employees = Employee::with(['position', 'attendances', 'leaves'])->get();

        foreach ($employees as $employee) {
            $basicSalary = $employee->position->basic_salary ?? 0;

            // Hitung jumlah kehadiran bulan ini
            $attendances = $employee->attendances()
                ->whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->where('status', 'hadir')
                ->count();

            // Hitung jumlah cuti bulan ini
            $leaves = $employee->leaves()
                ->whereYear('tanggal_mulai', $year)
                ->whereMonth('tanggal_mulai', $month)
                ->count();

            $insentif = $attendances * 10000;
            $bonus = 250000;

            $total = $basicSalary + $insentif + $bonus;

            Payroll::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'tahun' => $year,
                    'bulan' => $month,
                ],
                [
                    'gaji_pokok' => $basicSalary,
                    'jumlah_kehadiran' => $attendances,
                    'jumlah_cuti' => $leaves,
                    'insentif_kehadiran' => $insentif,
                    'bonus' => $bonus,
                    'total_gaji' => $total,
                    'status' => 'pending',
                ]
            );
        }
    }
}
