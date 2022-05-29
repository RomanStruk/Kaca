<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Kaca\Actions\CashierUser\CreateCashierUser;
use Kaca\Actions\CashierUser\DeleteCashierUser;
use Kaca\Actions\CashierUser\UpdateCashierUser;
use Kaca\Kaca;
use Kaca\Models\Cashier;
use Kaca\Models\CashRegister;
use function view;

class CashierUserController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the users.
     *
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('seniorPermission');
        $userModel = Kaca::$userModel;
        $userModel::resolveRelationUsing(
            'cashier',
            function ($userModel) {
                return $userModel->belongsTo(Cashier::class, 'cashier_id');
            }
        );
        $userModel::resolveRelationUsing(
            'cashRegister',
            function ($userModel) {
                return $userModel->belongsTo(CashRegister::class, 'cash_register_id');
            }
        );

        $cashierUsers = Kaca::newUserModel()
            ->with(['cashier', 'cashRegister'])
            ->whereNotNull(['cashier_id', 'cash_register_id'])
            ->paginate(15);
        return view('kaca::cashier-users.index', compact('cashierUsers'));
    }

    /**
     * Display editing form
     *
     * @throws AuthorizationException
     */
    public function edit(int $id): View
    {
        $this->authorize('seniorPermission');

        $cashierUser = Kaca::findUserByIdOrFail($id);
        $cashiers = Cashier::query()->get();
        $cashRegisters = CashRegister::query()->get();
        return view('kaca::cashier-users.edit', compact('cashierUser', 'cashiers', 'cashRegisters'));
    }

    /**
     * Update user data about the cash register and cashier
     *
     * @throws AuthorizationException
     */
    public function update(Request $request, int $id, UpdateCashierUser $updateCashierUser): RedirectResponse
    {
        $this->authorize('seniorPermission');

        $user = Kaca::findUserByIdOrFail($id);
        if ($updateCashierUser->update($user, $request->all())) {
            return redirect()->back()->with(['message' => 'Оновлено']);
        }
        return redirect()->back()->withErrors('Сталась помилка!');
    }

    /**
     * Form of adding a new user
     *
     * @throws AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('seniorPermission');

        $users = Kaca::newUserModel()->whereNull(['cashier_id', 'cash_register_id'])->get();
        $cashiers = Cashier::all();
        $cashRegisters = CashRegister::query()->get();
        return view('kaca::cashier-users.create', compact('users', 'cashiers', 'cashRegisters'));
    }

    /**
     * Add a new user to use the cash register
     *
     * @throws AuthorizationException
     */
    public function store(Request $request, CreateCashierUser $createCashierUser): RedirectResponse
    {
        $this->authorize('seniorPermission');

        if ($createCashierUser->create($request->all())) {
            return redirect()->route('kaca.cashier-users.index')->with(['message' => 'Користувач успішно доданий!']);
        }
        return back()->withErrors('Не вдалось додати користувача касиром!');
    }

    /**
     * Delete the cashier and cash register for the user
     *
     * @throws AuthorizationException
     */
    public function destroy(int $id, DeleteCashierUser $deleteCashierUser): RedirectResponse
    {
        $this->authorize('seniorPermission');

        $user = Kaca::findUserByIdOrFail($id);
        if ($deleteCashierUser->delete($user)) {
            return redirect()->route('kaca.cashier-users.index')->with(['message' => 'Користувач успішно видалений!']);
        }
        return back()->withErrors('Не вдалось видалити!');
    }
}
