<?php

declare(strict_types=1);

namespace Kaca;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Kaca\Actions\Cashier\SignInCashier;
use Kaca\Actions\Receipt\CreateLocalReceipt;
use Kaca\Actions\Receipt\CreateReceipt;
use Kaca\Actions\Receipt\SyncLocalReceipt;
use Kaca\Actions\Receipt\SyncReceiptStatus;
use Kaca\Actions\Report\CreateReport;
use Kaca\Actions\Shift\CloseShift;
use Kaca\Actions\Shift\OpenShift;
use Kaca\Actions\Shift\SyncShiftStatus;

class KacaApplicationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->gate();
    }

    public function boot(): void
    {
        Kaca::useUserModel(User::class);
        Kaca::userModelFieldNameUsing('name');

        Kaca::createCheckboxEntryUsing(CheckboxEntry::class);

        Kaca::openShiftUsing(OpenShift::class);
        Kaca::closeShiftUsing(CloseShift::class);
        Kaca::syncShiftStatusUsing(SyncShiftStatus::class);

        Kaca::createXReportUsing(CreateReport::class);

        Kaca::signInCashierUsing(SignInCashier::class);

        Kaca::createReceiptUsing(CreateReceipt::class);
        Kaca::createLocalReceiptUsing(CreateLocalReceipt::class);
        Kaca::syncLocalReceiptUsing(SyncLocalReceipt::class);
        Kaca::syncReceiptStatusUsing(SyncReceiptStatus::class);
    }

    /**
     * Register the Kaca gate.
     * This gate determines who can access Kaca.
     */
    protected function gate(): void
    {
        Gate::define('seniorPermission', function ($user) {
            return in_array($user->{$user->getKeyName()}, [1,]);
        });
        Gate::define('developerPermission', function ($user) {
            return in_array($user->{$user->getKeyName()}, [1,]);
        });
    }
}
