<?php

namespace Rabsana\Trade\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Rabsana\Trade\Console\Commands\GenerateOrdersRandomlyCommand;
use Rabsana\Trade\Console\Commands\MatchOrdersCommand;
use Rabsana\Trade\Console\Commands\SymbolChartCommand;
use Rabsana\Trade\Console\Commands\SymbolInfoCommand;
use Rabsana\Trade\Contracts\Interfaces\Trade;
use Rabsana\Trade\Trades\FIFO;

class TradeServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->registerPublishes();
        $this->registerResources();
    }

    public function register()
    {
        $this->loadCommands();
        $this->loadProviders();
    }

    protected function loadCommands(): TradeServiceProvider
    {
        $this->commands([
            GenerateOrdersRandomlyCommand::class,
            MatchOrdersCommand::class,
            SymbolChartCommand::class,
            SymbolInfoCommand::class
        ]);

        return $this;
    }

    protected function loadProviders(): TradeServiceProvider
    {
        $this->app->register(TradeEventServiceProvider::class);
        return $this;
    }

    protected function registerPublishes(): TradeServiceProvider
    {
        $this->publishConfigs()
            ->publishMigrations()
            ->publishAssets()
            ->publishLangs()
            ->publishViews()
            ->publishAll();

        return $this;
    }

    protected function publishConfigs(): TradeServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../config/rabsana-trade.php" => config_path('rabsana-trade.php')
        ], 'rabsana-trade-config');

        return $this;
    }

    protected function publishMigrations(): TradeServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../database/migrations/2021_06_25_130755_create_symbol_order_types_table.php"                                                            => database_path('migrations/2021_06_25_130755_create_symbol_order_types_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_132700_create_symbol_order_statuses_table.php"                                                         => database_path('migrations/2021_06_25_132700_create_symbol_order_statuses_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_132750_create_commissions_table.php"                                                                   => database_path('migrations/2021_06_25_132750_create_commissions_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_132755_create_commission_conditions_table.php"                                                         => database_path('migrations/2021_06_25_132755_create_commission_conditions_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_132800_create_symbols_table.php"                                                                       => database_path('migrations/2021_06_25_132800_create_symbols_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_132850_create_symbol_info_table.php"                                                                   => database_path('migrations/2021_06_25_132850_create_symbol_info_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_141600_create_pivot_symbol_symbol_order_type_table.php"                                                => database_path('migrations/2021_06_25_141600_create_pivot_symbol_symbol_order_type_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_142300_create_symbol_validation_table.php"                                                             => database_path('migrations/2021_06_25_142300_create_symbol_validation_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_144100_create_symbol_charts_table.php"                                                                 => database_path('migrations/2021_06_25_144100_create_symbol_charts_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_150400_create_symbol_orders_table.php"                                                                 => database_path('migrations/2021_06_25_150400_create_symbol_orders_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_150450_add_some_fields_to_symbol_orders_table.php"                                                     => database_path('migrations/2021_06_25_150450_add_some_fields_to_symbol_orders_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_150460_add_some_fields_to_symbol_orders_part_two_table.php"                                            => database_path('migrations/2021_06_25_150460_add_some_fields_to_symbol_orders_part_two_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_25_151800_create_symbol_order_trades_table.php"                                                           => database_path('migrations/2021_06_25_151800_create_symbol_order_trades_table.php'),
            __DIR__ . "/../../database/migrations/2021_06_26_151800_seed_rabsana_trade_package_data.php"                                                            => database_path('migrations/2021_06_26_151800_seed_rabsana_trade_package_data.php'),
            __DIR__ . "/../../database/migrations/2021_10_23_151800_add_average_price_source_is_market_to_symbol_validation_table.php"                              => database_path('migrations/2021_10_23_151800_add_average_price_source_is_market_to_symbol_validation_table.php'),
            __DIR__ . "/../../database/migrations/2021_10_30_151800_add_market_to_symbol_order_types_table.php"                                                     => database_path('migrations/2021_10_30_151800_add_market_to_symbol_order_types_table.php'),
            __DIR__ . "/../../database/migrations/2022_01_25_154600_add_external_order_id_field_to_symbol_orders_table.php"                                         => database_path('migrations/2022_01_25_154600_add_external_order_id_field_to_symbol_orders_table.php'),
        ], 'rabsana-trade-migrations');


        return $this;
    }

    protected function publishAssets(): TradeServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../assets/" => public_path('vendor/rabsana/trade')
        ], 'rabsana-trade-assets');

        return $this;
    }

    protected function publishLangs(): TradeServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../resources/lang" => resource_path("lang")
        ], 'rabsana-trade-langs');
        return $this;
    }

    protected function publishViews(): TradeServiceProvider
    {
        $this->publishes([
            __DIR__ . "/../../resources/views" => resource_path("views/vendor")
        ], 'rabsana-trade-views');
        return $this;
    }

    protected function publishAll(): TradeServiceProvider
    {
        $this->publishes(self::$publishes[TradeServiceProvider::class], 'rabsana-trade-publish-all');
        return $this;
    }

    protected function registerResources(): TradeServiceProvider
    {
        $this->registerMigrations()
            ->registerTranslations()
            ->registerViews()
            ->registerApiRoutes()
            ->registerChennelsRoutes()
            ->registerAdminApiRoutes()
            ->registerBinds();


        return $this;
    }

    protected function registerBinds(): TradeServiceProvider
    {
        $this->app->singleton(Trade::class, function ($app) {
            return new FIFO();
        });

        return $this;
    }

    protected function registerMigrations(): TradeServiceProvider
    {
        $this->loadMigrationsFrom(__DIR__ . "/../../database/migrations");
        return $this;
    }

    protected function registerTranslations(): TradeServiceProvider
    {
        $this->loadTranslationsFrom(__DIR__ . "/../../resources/lang", 'trade');
        return $this;
    }

    protected function registerViews(): TradeServiceProvider
    {
        $this->loadViewsFrom(__DIR__ . "/../../resources/views", 'rabsana-trade');
        return $this;
    }

    protected function registerApiRoutes(): TradeServiceProvider
    {
        Route::group($this->apiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/../../routes/api.php");
        });
        return $this;
    }

    protected function registerChennelsRoutes(): TradeServiceProvider
    {
        $this->loadRoutesFrom(__DIR__ . "/../../routes/channels.php");
        return $this;
    }

    protected function registerAdminApiRoutes(): TradeServiceProvider
    {
        Route::group($this->adminApiRouteConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . "/../../routes/admin-api.php");
        });
        return $this;
    }

    protected function apiRouteConfiguration(): array
    {
        return [
            'domain'        => config('rabsana-trade.domain', null),
            'namespace'     => NULL,
            'prefix'        => config('rabsana-trade.path', 'rabsana-trade'),
            'as'            => 'rabsana-trade.',
            'middleware'    => config('rabsana-trade.apiMiddlewares.group', 'api'),
        ];
    }

    protected function adminApiRouteConfiguration(): array
    {
        return [
            'domain'        => config('rabsana-trade.domain', null),
            'namespace'     => NULL,
            'prefix'        => config('rabsana-trade.path', 'rabsana-trade'),
            'as'            => 'rabsana-trade.',
            'middleware'    =>  config('rabsana-trade.adminApiMiddlewares.group', 'web'),
        ];
    }
}
