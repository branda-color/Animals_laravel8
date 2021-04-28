<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Laravel\Passport\Passport;
use App\Models\Animal;
use App\Policies\AnimalPolicy;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Animal::class => AnimalPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        //access_token設定15天過期
        Passport::tokensExpireIn(now()->addDay(15));

        //refresh_token設定15天過期
        Passport::refreshTokensExpireIn(now()->addDay(15));

        //定義伺服器的scope
        Passport::tokensCan([
            'create-animals' => '建立動物資訊', //key:scope名稱->value:scope說明
            'user-info' => '使用者資訊',
        ]);
    }
}
