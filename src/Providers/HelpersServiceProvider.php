<?php

namespace Tripteki\Helpers\Providers;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class HelpersServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        $this->app->bind(AuthModelContract::class, Auth::guard()->getProvider()->getModel());

        $this->registerConfigs();
        $this->registerPublishers();
    }

    /**
     * @return void
     */
    protected function registerConfigs()
    {
        $this->mergeConfigFrom(__DIR__."/../../config/helpers.php", "helpers");
    }

    /**
     * @return void
     */
    protected function registerPublishers()
    {
        $this->publishes(
        [
            __DIR__."/../../config/helpers.php" => config_path("helpers.php"),
        ],

        "tripteki-laravelphp-helpers-configs");
    }
};
