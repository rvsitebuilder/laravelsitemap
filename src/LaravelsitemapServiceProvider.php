<?php

namespace Rvsitebuilder\Laravelsitemap;

use GuzzleHttp\RequestOptions;
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
        $this->registerOverideConfig();
    }

    public function defineMigrate(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function registerOverideConfig(): void
    {
        // overide config
        $laravelsitemap_COOKIES = config('rvsitebuilder/laravelsitemap.COOKIES');
        $laravelsitemap_COOKIES = ($laravelsitemap_COOKIES == '1' ? true : $laravelsitemap_COOKIES);
        $laravelsitemap_ALLOW_REDIRECTS = config('rvsitebuilder/laravelsitemap.ALLOW_REDIRECTS');
        $laravelsitemap_ALLOW_REDIRECTS = ($laravelsitemap_ALLOW_REDIRECTS == '1' ? true : $laravelsitemap_ALLOW_REDIRECTS);

        Config::set([
            'override' => [
                'sitemap' => [
                    'guzzle_options' => [
                        RequestOptions::COOKIES => $laravelsitemap_COOKIES,
                        RequestOptions::CONNECT_TIMEOUT => config('rvsitebuilder/laravelsitemap.CONNECT_TIMEOUT'),
                        RequestOptions::TIMEOUT => config('rvsitebuilder/laravelsitemap.TIMEOUT'),
                        RequestOptions::ALLOW_REDIRECTS => $laravelsitemap_ALLOW_REDIRECTS,
                    ],
                ],
            ],
        ]);
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

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'rvsitebuilder/laravelsitemap');
    }
}
