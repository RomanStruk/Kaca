<?php

declare(strict_types=1);

namespace Kaca;

use Kaca\Contracts\CheckboxApi;
use Kaca\Models\Cashier;
use Kaca\Models\CashRegister;
use Kaca\Models\Receipt;
use Kaca\Models\Shift;
use Kaca\Policies\CashierPolicy;
use Kaca\Policies\CashRegisterPolicy;
use Kaca\Policies\ReceiptPolicy;
use Kaca\Policies\ShiftPolicy;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class KacaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/kaca.php', 'kaca');

        $this->app->singleton(CheckboxApi::class, function () {
            return new CheckboxApiClient(config('kaca'));
        });

        $this->app->booted(function () {
            if ($this->app->runningInConsole()) {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command('kaca:process')->everyMinute(); // checkbox перевірка статусів чеків
                $schedule->command('kaca:synchronizing')->everyFiveMinutes();
            }
        });
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views/' . config('kaca.stack'), 'kaca');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadJsonTranslationsFrom(__DIR__ . '/../resources/lang');

        $this->configurePolicies();
        $this->configureComponents();
        $this->configurePublishes();
        $this->configureRoutes();
        $this->registerCommands();

        // відключити ліниву підгрузку, N+1 при розробці
//        Model::preventLazyLoading(!$this->app->isProduction());
    }

    /**
     * Налаштування роутів
     */
    protected function configureRoutes(): void
    {
        Route::middlewareGroup('kaca', config('kaca.middleware', ['web']));

        Route::group([
            'namespace' => '',
            'domain' => config('kaca.domain'),
            'prefix' => config('kaca.prefix'),
            'middleware' => 'kaca',
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/kaca.php');
        });
    }

    /**
     * Налаштування файлів пакету для публікації
     */
    protected function configurePublishes(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/kaca.php' => config_path('kaca.php'),
        ], 'kaca-config');

        $this->publishes([
            __DIR__ . '/../routes/kaca.php' => base_path('routes/kaca.php'),
        ], 'kaca-routes');

        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194753_create_cash_registers_table.php' => database_path('migrations/2022_01_04_194753_create_cash_registers_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194755_create_cashiers_table.php' => database_path('migrations/2022_01_04_194755_create_cashiers_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194756_create_shifts_table.php' => database_path('migrations/2022_01_04_194756_create_shifts_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194757_create_receipts_table.php' => database_path('migrations/2022_01_04_194757_create_receipts_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194758_create_receipt_goods_table.php' => database_path('migrations/2022_01_04_194758_create_receipt_goods_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194759_create_checkbox_entries_table.php' => database_path('migrations/2022_01_04_194759_create_checkbox_entries_table.php'),
        ], 'kaca-migrations');
        $this->publishes([
            __DIR__ . '/../database/migrations/2022_01_04_194760_add_cashier_field_to_users_table.php' => database_path('migrations/2022_01_04_194760_add_cashier_field_to_users_table.php'),
        ], 'kaca-install-migrations');

        $this->publishes([
            __DIR__ . '/../stubs/KacaApplicationServiceProvider.stub' => app_path('Providers/KacaApplicationServiceProvider.php'),
        ], 'kaca-provider');

        $this->publishes([
            __DIR__ . '/../resources/views/tailwind' => resource_path('views/vendor/kaca'),
        ], 'kaca-views-tailwind');

        $this->publishes([
            __DIR__ . '/../resources/views/adminlte3' => resource_path('views/vendor/kaca'),
        ], 'kaca-views-adminlte3');
    }

    /**
     * Підключення компонентів
     */
    protected function configureComponents(): void
    {
        $this->callAfterResolving(BladeCompiler::class, function (): void {
            $this->registerComponent('card');
            $this->registerComponent('validation-errors');
            $this->registerComponent('input');
            $this->registerComponent('label');
            $this->registerComponent('breadcrumbs');
            $this->registerComponent('breadcrumb-item');
            $this->registerComponent('alerts');
            $this->registerComponent('modal');
            $this->registerComponent('table');
            $this->registerComponent('table-td');
            $this->registerComponent('table-th');
            $this->registerComponent('table-tr');
        });
    }

    /**
     * Реєстрація blade компонентів
     */
    protected function registerComponent(string $component): void
    {
        Blade::component('kaca::components.' . $component, 'kaca-' . $component);
    }

    /**
     * Підключення консольних команд
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\InstallCommand::class,
                Console\ProcessingCommand::class,
                Console\SynchronizingCommand::class,
                Console\OpenShiftCommand::class,
            ]);
        }
    }

    /**
     * Підключення політик
     */
    protected function configurePolicies(): void
    {
        Gate::policy(Cashier::class, CashierPolicy::class);
        Gate::policy(Shift::class, ShiftPolicy::class);
        Gate::policy(Receipt::class, ReceiptPolicy::class);
        Gate::policy(CashRegister::class, CashRegisterPolicy::class);
    }
}
