<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Kaca\Actions\Cashier\DeleteLocalCashier;
use Kaca\Actions\Cashier\UpdateOrCreateCashier;
use Kaca\Contracts\Cashier\SignInsCashiers;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Exception\CheckboxInvalidCredentialsException;
use Kaca\Models\Cashier;
use function view;

class CashierController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the cashiers.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('seniorPermission');

        $cashiers = Cashier::query()->latest()->paginate(10);

        return view('kaca::cashiers.index', compact('cashiers'));
    }

    /**
     * Форма додавання касира
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(): View
    {
        $this->authorize('seniorPermission');

        return view('kaca::cashiers.create');
    }

    /**
     * Додавання нового касира
     *
     * @throws \Kaca\Contracts\CheckboxExceptions
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request, SignInsCashiers $signInCashier): RedirectResponse
    {
        $this->authorize('seniorPermission');

        try {
            $accessToken = $signInCashier->signIn($request->all());

            app(UpdateOrCreateCashier::class)
                ->handle($accessToken, $request->user());

            return redirect()->route('kaca.cashiers.index')
                ->with(['message' => 'Касира успішно додано!']);

        } catch (CheckboxInvalidCredentialsException $validationException) {
            throw ValidationException::withMessages([
                'credentials' => $validationException->getMessage(),
            ]);
        } catch (CheckboxExceptions $checkboxExceptions) {
            return redirect()->back()
                ->withErrors($checkboxExceptions->getMessage());
        }
    }

    /**
     * Видалення локальних даних про касира
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, Cashier $cashier, DeleteLocalCashier $deleteLocalCashier): RedirectResponse
    {
        $this->authorize('delete', $cashier);

        if ($deleteLocalCashier->delete($request->user(), $cashier)) {
            return redirect()->route('kaca.cashiers.index')
                ->with(['message' => 'Касир успішно видалений!']);
        }
        return back()->withErrors('Не вдалось видалити!');
    }
}
