<?php

namespace App\Providers;

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
       \Illuminate\Support\Facades\Gate::define('manage-content',
            fn (\App\Models\User $user) => in_array((int) $user->roleid, [1, 3], true));
        \Illuminate\Support\Facades\Gate::define('manage-users',
            fn (\App\Models\User $user) => (int) $user->roleid === 1);
    }
}
