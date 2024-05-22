<?php

namespace Modules\Saas\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Facades\Event;
use Modules\Saas\Events\InstituteRegistration;
use Modules\Saas\Listeners\InstituteRegisterdListener;

class SaasServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
//        $this->registerFactories();
        $this->loadMigrationsFrom(module_path('Saas', 'Database/Migrations'));

        Event::listen(
            InstituteRegistration::class,
            [InstituteRegisterdListener::class, 'handle']
        );

        
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path('Saas', 'Config/config.php') => config_path('saas.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path('Saas', 'Config/config.php'), 'saas'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/saas');

        $sourcePath = module_path('Saas', 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/saas';
        }, \Config::get('view.paths')), [$sourcePath]), 'saas');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/saas');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'saas');
        } else {
            $this->loadTranslationsFrom(module_path('Saas', 'Resources/lang'), 'saas');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path('Saas', 'Database/factories'));
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
