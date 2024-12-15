<?php

namespace App\Providers;

use App\Models\Profile;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Menggunakan View Composer untuk semua view
        View::composer('*', function ($view) {
            $profile = Profile::first() ?? new Profile(); // Gunakan instance kosong jika tidak ada data
            $view->with('profile', $profile);
        });
    }
}
