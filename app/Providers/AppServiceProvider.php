<?php

namespace App\Providers;

use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Setting;
use App\Policies\AppointmentPolicy;
use App\Policies\PetPolicy;
use App\Services\Payments\PaymentManager;
use App\Services\Sms\SmsManager;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SmsManager::class);
        $this->app->singleton(PaymentManager::class);
    }

    public function boot(): void
    {
        Gate::policy(Appointment::class, AppointmentPolicy::class);
        Gate::policy(Pet::class, PetPolicy::class);

        Paginator::defaultView('partials.pagination');

        RateLimiter::for('booking', fn (Request $r) => Limit::perMinute(10)->by($r->ip()));
        RateLimiter::for('contact', fn (Request $r) => Limit::perMinute(5)->by($r->ip()));
        RateLimiter::for('otp', fn (Request $r) => [
            Limit::perMinute(3)->by('otp:'.$r->input('phone').$r->ip()),
            Limit::perHour(10)->by('otp-ip:'.$r->ip()),
        ]);
        RateLimiter::for('login', fn (Request $r) => Limit::perMinute(6)->by($r->input('login').$r->ip()));

        View::composer('*', function ($view) {
            $view->with('settings', Setting::all_());
        });
    }
}
