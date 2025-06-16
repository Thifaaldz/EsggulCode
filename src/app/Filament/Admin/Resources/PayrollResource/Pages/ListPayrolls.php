<?php

namespace App\Filament\Admin\Resources\PayrollResource\Pages;

use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\Payroll;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Actions\Action;
use App\Filament\Admin\Resources\PayrollResource;

class ListPayrolls extends ListRecords
{
    protected static string $resource = PayrollResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generatePayroll')
                ->label('Generate Otomatis')
                ->action('generatePayroll')
                ->requiresConfirmation()
                ->color('success'),
        ];
    }

    public function generatePayroll()
    {
        $bulan = now()->format('m');
        $tahun = now()->format('Y');

        $startDate = Carbon::create($tahun, $bulan)->startOfMonth();
        $endDate = Carbon::create($tahun, $bulan)->endOfMonth();

        $employees = Employee::with('position')->get();

        foreach ($employees as $employee) {
            $hadir = Attendance::where('employee_id', $employee->id)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->count();

            $cuti = Leave::where('employee_id', $employee->id)
                ->where('status', 'disetujui')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                          ->orWhereBetween('tanggal_selesai', [$startDate, $endDate]);
                })
                ->count();

            $gajiPokok = $employee->position->basic_salary ?? 0;
            $insentif = $hadir * 10000;
            $bonus = 250000;
            $total = $gajiPokok + $insentif + $bonus;

            Payroll::updateOrCreate(
                ['employee_id' => $employee->id, 'bulan' => $bulan, 'tahun' => $tahun],
                [
                    'gaji_pokok' => $gajiPokok,
                    'jumlah_kehadiran' => $hadir,
                    'jumlah_cuti' => $cuti,
                    'insentif_kehadiran' => $insentif,
                    'bonus' => $bonus,
                    'total_gaji' => $total,
                    'status' => 'pending',
                ]
            );
        }

        Notification::make()
            ->title('Sukses')
            ->body('Data penggajian bulan ini berhasil digenerate.')
            ->success()
            ->send();
    }
}
