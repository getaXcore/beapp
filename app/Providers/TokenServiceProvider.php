<?php
use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

/**
 * Bootstrap any application services.
 *
 * @return void
 */
class TokenServiceProvider extends ServiceProvider{

    public function boot(){
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
    }
}