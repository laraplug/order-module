<?php

namespace Modules\Order\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Order\Events\Handlers\RegisterOrderSidebar;

class OrderServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterOrderSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('orders', array_dot(trans('order::orders')));
            $event->load('orderstatuses', array_dot(trans('order::orderstatuses')));
            $event->load('transactions', array_dot(trans('order::transactions')));
            $event->load('transportations', array_dot(trans('order::transportations')));
            // append translations


        });
    }

    public function boot()
    {
        $this->publishConfig('order', 'config');
        $this->publishConfig('order', 'permissions');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Order\Repositories\OrderRepository',
            function () {
                $repository = new \Modules\Order\Repositories\Eloquent\EloquentOrderRepository(new \Modules\Order\Entities\Order());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Order\Repositories\Cache\CacheOrderDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Order\Repositories\OrderStatusRepository',
            function () {
                $repository = new \Modules\Order\Repositories\Eloquent\EloquentOrderStatusRepository(new \Modules\Order\Entities\OrderStatus());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Order\Repositories\Cache\CacheOrderStatusDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Order\Repositories\TransactionRepository',
            function () {
                $repository = new \Modules\Order\Repositories\Eloquent\EloquentTransactionRepository(new \Modules\Order\Entities\Transaction());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Order\Repositories\Cache\CacheTransactionDecorator($repository);
            }
        );
        $this->app->bind(
            'Modules\Order\Repositories\TransportationRepository',
            function () {
                $repository = new \Modules\Order\Repositories\Eloquent\EloquentTransportationRepository(new \Modules\Order\Entities\Transportation());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Order\Repositories\Cache\CacheTransportationDecorator($repository);
            }
        );
// add bindings




    }
}
