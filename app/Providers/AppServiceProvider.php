<?php

namespace App\Providers;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
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
        Gate::define(
            ability: 'approve-flight',
            callback: fn(User $user, Flight $flight) => !$this->isFlightBelongsToUser($user, $flight)
        );

        Gate::define(
            ability: 'cancel-flight',
            callback: function (User $user, Flight $flight) {
                if ($flight->isRequested() && $this->isFlightBelongsToUser($user, $flight)) {
                    return true;
                }

                return !$this->isFlightBelongsToUser($user, $flight);
            }
        );
    }

    private function isFlightBelongsToUser(User $user, Flight $flight): bool
    {
        return $user->id === $flight->user_id;
    }
}
