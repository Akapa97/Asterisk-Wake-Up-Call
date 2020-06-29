<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
       $schedule->call(function () {
            $timestamp = strtotime(date('Y-m-d H:i:00'));

            $sql = "SELECT * FROM wake_up_calls WHERE datetime = ?";

            $result = DB::select($sql, [$timestamp]);

            foreach($result as $row) {
                $file = fopen("/var/spool/asterisk/outgoing/{$row->ext}.call","w");

                $txt = "Channel:PJSIP/{$row->ext}";
                $txt .= "\nContext:WakeUpCall";
                $txt .= "\nCallerID:'WakeUP' <WAKEUP_CALL>";
                $txt .= "\nExtension: s";
                $txt .= "\nPriority: 1";
                $txt .= "\nMaxRetries: {$row->tries}";
                $txt .= "\nWaitTime: {$row->waittime}";
                $txt .= "\nRetryTime: {$row->retrytime}";
                $txt .= "\nSetVar: QUARTO={$row->ext}";
                $txt .= "\nSetVar: SUPERVISOR={$row->supervisor}";
                $txt .= "\nSetVar: TELEF_SUPERV=1";

                fwrite($file, $txt);

                fclose($file);
            }

            $sql = "DELETE FROM wake_up_calls WHERE datetime <= ?";

            $result = DB::delete($sql, [$timestamp]);
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
