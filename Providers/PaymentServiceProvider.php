<?php

namespace Modules\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Payment\Repositories\PaymentRepository;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
        $this->registerFactories();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerBreintree();
    }

    /**
     * Register Braintree configuration
     *
     * @return void
     */
    public function registerBreintree()
    {
        if (config('netcore.module-payment.braintree.enabled'))
        {
            \Braintree_Configuration::environment(config('netcore.module-payment.braintree.environment'));
            \Braintree_Configuration::merchantId(config('netcore.module-payment.braintree.merchant_id'));
            \Braintree_Configuration::publicKey(config('netcore.module-payment.braintree.public_key'));
            \Braintree_Configuration::privateKey(config('netcore.module-payment.braintree.private_key'));
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('payment', function ($app) {
            return new PaymentRepository();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('netcore/module-payment.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'netcore/module-payment'
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/payment');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/payment';
        }, \Config::get('view.paths')), [$sourcePath]), 'payment');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/payment');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'payment');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'payment');
        }
    }

    /**
     * Register an additional directory of factories.
     * @source https://github.com/sebastiaanluca/laravel-resource-flow/blob/develop/src/Modules/ModuleServiceProvider.php#L66
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Laravel\Cashier\CashierServiceProvider::class
        ];
    }
}
