<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use Carbon\Carbon;

class GeneratePayroll extends Command
{
    protected $signature = 'payroll:generate {--month=} {--year=}';
    protected $description = 'Generate payroll for all employees for a specific month and year';

    public function handle()
    {
        $month = $this->option('month') ?? now()->format('m');
        $year = $this->option('year') ?? now()->format('Y');

        $startDate = Carbon::create($year, $month)->startOfMonth();
        $endDate = Carbon::create($year, $month)->endOfMonth();

        $employees = Employee::with('position')->get();

        foreach ($employees as $employee) {
            $absensiCount = Attendance::where('employee_id', $employee->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->where('status', 'hadir')
                ->count();

            $cutiCount = Leave::where('employee_id', $employee->id)
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                          ->orWhereBetween('tanggal_selesai', [$startDate, $endDate]);
                })
                ->where('status', 'disetujui')
                ->count();

            $basicSalary = $employee->position->basic_salary;
            $absentBonus = $absensiCount * 10000;
            $bonus = 250000;
            $total = $basicSalary + $absentBonus + $bonus;

            Payroll::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'bulan' => $month,
                    'tahun' => $year,
                ],
                [
                    'gaji_pokok' => $basicSalary,
                    'jumlah_kehadiran' => $absensiCount,
                    'jumlah_cuti' => $cutiCount,
                    'insentif_kehadiran' => $absentBonus,
                    'bonus' => $bonus,
                    'total_gaji' => $total,
                    'status' => 'pending',
                ]
            );
        }

        $this->info("Payroll berhasil digenerate untuk bulan $month/$year.");
    }
}
