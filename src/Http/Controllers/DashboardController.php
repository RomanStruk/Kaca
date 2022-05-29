<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Kaca\Kaca;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use function view;

class DashboardController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     *
     * @throws AuthorizationException
     */
    public function __invoke(Request $request): View
    {
        $this->authorize('view', $cashier = Kaca::findCashierByCashierUser($request->user()));

        $cashRegister = Kaca::findCashRegisterByCashierUser($request->user());
        $shift = Kaca::findShiftByCashierUser($request->user());
        $shift->load('synchronization');

        $receipts = $shift->receipts()->latest()->paginate(15);
        return view('kaca::home.index', compact('cashier', 'cashRegister', 'shift', 'receipts'));
    }
}
