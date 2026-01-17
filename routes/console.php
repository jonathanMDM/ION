<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule automatic backups
Schedule::command('backup:run --only-db')->dailyAt('02:00');

// Low stock alerts
Schedule::command('alerts:check-low-stock')->dailyAt('08:00');

// Maintenance alerts
Schedule::command('assets:check-maintenance')->dailyAt('08:30');

// Weekly digest (Weekly on Mondays)
Schedule::command('reports:send-weekly-digest')->weeklyOn(1, '09:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
