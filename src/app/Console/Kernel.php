<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

// Import command payroll
use App\Console\Commands\GeneratePayroll;

class Kernel extends ConsoleKernel
{
    /**
     * Register the commands for the application.
     */
    protected $commands = [
        GeneratePayroll::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Contoh jika ingin jalankan otomatis tiap bulan
        // $schedule->command('payroll:generate --month=' . now()->format('m') . ' --year=' . now()->format('Y'))->monthly();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
