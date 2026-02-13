<?php

namespace App\Providers;

use App\Models\SheetSource;
use App\Policies\SheetSourcePolicy;
use Illuminate\Support\ServiceProvider;

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
        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        \Illuminate\Support\Facades\Gate::policy(SheetSource::class, SheetSourcePolicy::class);
    }
}
