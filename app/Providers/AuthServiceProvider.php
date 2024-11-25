<?php

namespace App\Providers;
use Laravel\Passport\Passport;
use App\Permissions\Abilities;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

       // Passport::routes();
        //
        $abilities = new Abilities();
          // Register abilities dynamically
         // var_dump($abilities::getAllAbilities());
          Passport::tokensCan(abilities::getAllAbilities());

          Passport::setDefaultScope($abilities->getDefaultScope());
    }
}
