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
        $schedule->command('payroll:generate')->monthlyOn(1, '01:00');
        $schedule->command('attendance:from-leave')->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
