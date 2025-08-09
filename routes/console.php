<?php

use App\Console\Commands\UpdateEventStatus;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Schedule commands to run here
 */
// Schedule::command(ExampleCommand::class)->hourly();
