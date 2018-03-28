<?php

namespace Modules\Payment\Providers;

use Exception;
use Braintree_Configuration;
use Illuminate\Support\ServiceProvider;
use Modules\Payment\Repositories\PaymentRepository;
use Modules\Setting\Repositories\SettingRepository;

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
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->registerBraintree();
    }

    /**
     * Register Braintree configuration
     *
     * @return void
     */
    public function registerBraintree()
    {
        try {
            if (config('netcore.module-payment.braintree.enabled')) {
                /** @var $settings SettingRepository */
                $settings = app(SettingRepository::class);

                Braintree_Configuration::environment($settings->get('braintree_environment'));
                Braintree_Configuration::merchantId($settings->get('braintree_merchant_id'));
                Braintree_Configuration::publicKey($settings->get('braintree_public_key'));
                Braintree_Configuration::privateKey($settings->get('braintree_private_key'));
            }
        } catch (Exception $e) {
            logger()->critical('[Module-Payment] @ PaymentServiceProvider - ' . $e->getMessage());
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
            __DIR__ . '/../Config/config.php' => config_path('netcore/module-payment.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__ . '/../Config/config.php', 'netcore/module-payment'
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

        $sourcePath = __DIR__ . '/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/payment';
        }, config('view.paths')), [$sourcePath]), 'payment');
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
            $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'payment');
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
            Laravel\Cashier\CashierServiceProvider::class,
        ];
    }
}
