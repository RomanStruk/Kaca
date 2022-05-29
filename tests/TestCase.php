<?php

namespace Kaca\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Kaca\CheckboxApiFacade;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\KacaServiceProvider;
use Kaca\Models\Cashier;
use Kaca\Models\Shift;
use Kaca\Tests\stubs\KacaApplicationServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // реєстрація папки для view щоб унитнути проблему з відсутністю файлів
        $this->app['view']->addLocation(__DIR__ . '/../resources/views/');
        $this->app['blade.compiler']->component('layouts.app', 'app-layout');
    }


    public function getEnvironmentSetUp($app)
    {
        include_once __DIR__ . '/stubs/create_users_table.php.stub';

        // run the up() method (perform the migration)
        (new \CreateUsersTable)->up();
    }

    protected function getPackageProviders($app)
    {
        return [
            KacaApplicationServiceProvider::class,
            KacaServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'CheckboxApiFacade' => CheckboxApiFacade::class,
        ];
    }

    /**
     * Створення користувача, авторизація, призначення касиром
     */
    protected function setUpCashier(array $attributes = []): \Kaca\Models\Cashier
    {
        $user = UserFactory::new($attributes)->withCashier()->create();
        $this->actingAs($user);
        return Cashier::where('id', $user->cashier_id)->firstOrFail();
    }

    /**
     * Create shift with fake cashier and cash-register and associated with auth user
     */
    protected function setUpOpenedShiftWithUser(array $attributes = []): Shift
    {
        $shift = ShiftFactory::new($attributes)
            ->forOpenedStatus()
            ->afterCreating(function ($shift) {
                \Kaca\Synchronization::finish($shift->id);
            })->create();
        $user = UserFactory::new(['cashier_id' => $shift->cashier_id, 'cash_register_id' => $shift->cash_register_id])->create();
        $this->actingAs($user);

        return $shift;
    }
}
