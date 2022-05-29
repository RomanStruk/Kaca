<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Kaca\Actions\CashRegister\DeleteLocalCashRegister;
use Kaca\Actions\CashRegister\SyncCashRegister;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Exception\CheckboxValidationException;
use Kaca\Models\CashRegister;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;
use function view;

class CashRegisterController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the cash registers.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('seniorPermission');

        $cashRegisters = CashRegister::query()->paginate(10);
        return view('kaca::cash-registers.index', compact('cashRegisters'));
    }

    /**
     * Форма створення каси
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('seniorPermission');

        return view('kaca::cash-registers.create');
    }

    /**
     * Збереження локальної каси
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('seniorPermission');

        $validated = Validator::make($request->all(), [
            'licence_key' => ['required', 'string', 'max:255'],
        ])->validate();

        try {
            app(SyncCashRegister::class)->sync($request->user(), $validated['licence_key']);

            return redirect()->route('kaca.cash-registers.index')
                ->with('message', 'Каса успішно синхронізована!');
        } catch (CheckboxValidationException $validationException) {
            throw ValidationException::withMessages([$validationException->getMessage(),]);
        } catch (CheckboxExceptions $checkboxExceptions) {
            return redirect()->back()->withErrors($checkboxExceptions->getMessage());
        }
    }

    /**
     * Видалення локальної каси
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, CashRegister $cashRegister): RedirectResponse
    {
        $this->authorize('delete', $cashRegister);

        if (app(DeleteLocalCashRegister::class)->delete($request->user(), $cashRegister)) {
            return redirect()->route('kaca.cash-registers.index')
                ->with('message', 'Каса успішно видалено');
        }
        return redirect()->back()->withErrors('Не вдалось видалити касу!');
    }
}
