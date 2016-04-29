<?php

namespace Bayri\LaravelExtender;

use Illuminate\Support\ServiceProvider;
use Bayri\LaravelExtender\Blade\DirectiveGenerator;

class BladeExtenderServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function boot()
    {
        $this->app->make(DirectiveGenerator::class)->generate();
    }
}
