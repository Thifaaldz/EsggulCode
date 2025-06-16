<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Leave;
use App\Models\Attendance;
use Carbon\Carbon;

class GenerateLeaveAttendances extends Command
{
    protected $signature = 'attendance:from-leave';
    protected $description = 'Generate absensi otomatis berdasarkan cuti yang disetujui';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $leaves = Leave::where('status', 'disetujui')
            ->whereDate('tanggal_mulai', '<=', $today)
            ->whereDate('tanggal_selesai', '>=', $today)
            ->get();

        foreach ($leaves as $leave) {
            Attendance::firstOrCreate([
                'employee_id' => $leave->employee_id,
                'tanggal' => $today,
            ], [
                'status' => 'cuti', // Atau bisa buat status baru: 'cuti'
            ]);
        }

        $this->info('Absensi cuti berhasil diproses.');
    }
}
