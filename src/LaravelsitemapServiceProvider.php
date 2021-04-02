<?php

namespace Rvsitebuilder\Laravelsitemap;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Spatie\Sitemap\SitemapServiceProvider;

class LaravelsitemapServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->bootRoute();
        $this->bootViews();
        $this->bootTranslations();
        $this->defineMigrate();
        $this->defineVendorPublish();
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    public function defineMigrate(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * Boot Route.
     */
    public function bootRoute(): void
    {
        include_route_files(__DIR__ . '/../routes');
    }

    /**
     * boot PublishAsset.
     */
    public function defineVendorPublish(): void
    {
        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/rvsitebuilder/laravelsitemap'),
        ], 'public');
    }

    /**
     * Register views.
     */
    public function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'rvsitebuilder/laravelsitemap');
    }

    /**
     * Register translations.
     */
    public function bootTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'rvsitebuilder/laravelsitemap');
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        // Load vendor service provider
        $this->app->register(SitemapServiceProvider::class);

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'rvsitebuilder.laravelsitemap');
    }
}
