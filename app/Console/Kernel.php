<?php

namespace App\Console;

use App\Jobs\PaymentStatusChecker;
use App\Jobs\ShelfStocker;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->job(new ShelfStocker, 'shelf-stocker')->everyFiveMinutes();
        $schedule->job(new PaymentStatusChecker, 'paymen-status')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
