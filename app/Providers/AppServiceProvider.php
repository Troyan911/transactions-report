<?php

namespace App\Providers;

use App\Services\Contracts\ImportCsvServiceContract;
use App\Services\Contracts\ReportServiceContract;
use App\Services\ImportCsvService;
use App\Services\ReportService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ReportServiceContract::class, ReportService::class);
        $this->app->bind(ImportCsvServiceContract::class, ImportCsvService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
