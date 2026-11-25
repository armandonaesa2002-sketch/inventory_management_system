<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Blade::directive('statusColor', function ($status) {
            return "<?php
                echo match($status) {
                    'Available' => 'stat-available',
                    'Assigned' => 'stat-assigned',
                    'For Repair' => 'stat-repair',
                    'Decommissioned' => 'stat-decom',
                    'Lost' => 'stat-lost',
                    'In Use' => 'stat-assigned',
                    'In progress' => 'stat-assigned',
                    'Cancelled' => 'stat-lost',
                    'Completed' => 'stat-available',
                    'Returned' => 'stat-available',
                    default => 'status-other',
                };
            ?>";
        });
    }
}
