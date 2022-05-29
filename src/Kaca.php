<?php
declare(strict_types=1);

namespace Kaca;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Kaca\Contracts\Cashier\SignInsCashiers;
use Kaca\Contracts\CheckboxEntries;
use Kaca\Contracts\Receipt\CreatesLocalReceipts;
use Kaca\Contracts\Receipt\CreatesReceipts;
use Kaca\Contracts\Receipt\SyncLocalReceipts;
use Kaca\Contracts\Receipt\SyncReceiptsStatuses;
use Kaca\Contracts\Report\CreatesXReports;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Contracts\Shift\SyncShiftsStatuses;
use Kaca\Models\Cashier;
use Kaca\Models\CashRegister;
use Kaca\Models\Receipt;
use Kaca\Models\Shift;

class Kaca
{
    /**
     * The user model that should be used by Checkbox kaca.
     */
    public static string $userModel = 'App\\Models\\User';

    /**
     * The user field name that should be used by Checkbox kaca.
     */
    public static string $userFieldName = 'name';

    /**
     * Set field name that should be used to get username.
     */
    public static function userModelFieldNameUsing(string $field): void
    {
        self::$userFieldName = $field;
    }

    /**
     * Register a class / callback that should be used to create teams.
     */
    public static function createCheckboxEntryUsing(string $class): void
    {
        app()->singleton(CheckboxEntries::class, $class);
    }

    /**
     * Register a class / callback that should be used to open shifts.
     */
    public static function openShiftUsing(string $class): void
    {
        app()->singleton(OpenShifts::class, $class);
    }

    /**
     * Register a class / callback that should be used to close shifts.
     */
    public static function closeShiftUsing(string $class): void
    {
        app()->singleton(CloseShifts::class, $class);
    }

    /**
     * Register a class / callback that should be used to sync shifts status.
     */
    public static function syncShiftStatusUsing(string $class): void
    {
        app()->singleton(SyncShiftsStatuses::class, $class);
    }

    /**
     * Register a class / callback that should be used to create receipt.
     */
    public static function createReceiptUsing(string $class): void
    {
        app()->singleton(CreatesReceipts::class, $class);
    }

    /**
     * Register a class / callback that should be used to SignIn Cashier.
     */
    public static function signInCashierUsing(string $class): void
    {
        app()->singleton(SignInsCashiers::class, $class);
    }

    /**
     * Register a class / callback that should be used to create local receipt.
     */
    public static function createLocalReceiptUsing(string $class): void
    {
        app()->singleton(CreatesLocalReceipts::class, $class);
    }

    /**
     * Register a class / callback that should be used to sync receipt.
     */
    public static function syncLocalReceiptUsing(string $class): void
    {
        app()->singleton(SyncLocalReceipts::class, $class);
    }

    /**
     * Register a class / callback that should be used to sync receipt status.
     */
    public static function syncReceiptStatusUsing(string $class): void
    {
        app()->singleton(SyncReceiptsStatuses::class, $class);
    }

    /**
     * Register a class / callback that should be used to create x report.
     */
    public static function createXReportUsing(string $class)
    {
        app()->singleton(CreatesXReports::class, $class);
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param int $id
     * @return Authenticatable|Model
     */
    public static function findUserByIdOrFail(int $id)
    {
        return Kaca::newUserModel()->where('id', $id)->firstOrFail();
    }

    /**
     * Get a new instance of the user model.
     *
     * @return Authenticatable|Model|Builder
     */
    public static function newUserModel()
    {
        $model = Kaca::userModel();

        return new $model();
    }

    /**
     * Get the name of the user model used by the application.
     */
    public static function userModel(): string
    {
        return Kaca::$userModel;
    }

    /**
     * Specify the user model that should be used by Checkbox Kaca.
     */
    public static function useUserModel(string $model): self
    {
        Kaca::$userModel = $model;

        return new Kaca();
    }

    /**
     * Find a shift instance by the given CashierUser.
     */
    public static function findShiftByCashierUser(Authenticatable $authenticatable): Shift
    {
        $cashier = self::findCashierByCashierUser($authenticatable);
        return $cashier->getShift();
    }

    /**
     * Find a cashier instance by the given CashierUser.
     *
     * @param Authenticatable|Model $cashierUser
     * @return Cashier|Model|null
     */
    public static function findCashierByCashierUser(Authenticatable $cashierUser): ?Cashier
    {
        return Cashier::query()->where('id', '=', $cashierUser->cashier_id)->first();
    }

    /**
     * Find a CashRegister instance by the given CashierUser.
     *
     * @param Authenticatable|Model $cashierUser
     *
     * @return CashRegister|Model|null
     */
    public static function findCashRegisterByCashierUser(Authenticatable $cashierUser): ?CashRegister
    {
        return CashRegister::query()->where('id', '=', $cashierUser->cash_register_id)->first();
    }

    /**
     * Find shifts for sync
     */
    public static function findShiftsForSync(): \Illuminate\Support\Collection
    {
        return Synchronization::findWithStatus(Shift::class, Synchronization::STATUS_CREATED);
    }

    /**
     * Find receipts for sync
     */
    public static function findReceiptsForSync(): \Illuminate\Support\Collection
    {
        return Synchronization::findWithStatus(Receipt::class, Synchronization::STATUS_CREATED);
    }

    /**
     * Determine if the given request can access the Kaca.
     */
    public static function check(Request $request): bool
    {
        $user = $request->user();

        return !is_null($user) && (!is_null($user->cashier_id) || Gate::forUser($user)->allows('seniorPermission'));
    }

    public static function hasNotifications(): bool
    {
        return true;
    }
}
