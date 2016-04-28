<?php

namespace Bayri\LaravelExtender;

use Bayri\LaravelExtender\Blade\DirectiveGenerator;
use Illuminate\Support\ServiceProvider;

class BladeExtenderServiceProvider extends ServiceProvider
{
    protected $directives;

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
