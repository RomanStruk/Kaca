<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Kaca\Contracts\Shift\CloseShifts;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Kaca;
use Kaca\Models\Shift;

class ShiftsController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Index
     *
     * @throws AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('seniorPermission');

        $shifts = Shift::query()->orderByDesc('created_at')->paginate(15);

        return view('kaca::shifts.index', compact('shifts'));
    }

    /**
     * Handle an opening shift.
     *
     * @throws AuthorizationException
     */
    public function store(Request $request, OpenShifts $openShift): RedirectResponse
    {
        $this->authorize('open', Kaca::findShiftByCashierUser($request->user()));

        try {
            $openShift->open($request->user(), Str::uuid()->toString());

            return redirect()->back()->with('message', 'Зміна відкривається!');
        } catch (CheckboxExceptions $exception) {
            return redirect()->back()->withErrors($exception->getMessage());
        }
    }

    /**
     * Handle a closing shift.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, CloseShifts $closeShifts): RedirectResponse
    {
        $this->authorize('close', $shift = Kaca::findShiftByCashierUser($request->user()));

        try {
            $closeShifts->close($request->user(), $shift);

            return redirect()->back()->with('message', 'Зміна: №' . $shift->serial . ' закривається...');
        } catch (CheckboxExceptions $checkboxExceptions) {
            return redirect()->back()->withErrors($checkboxExceptions->getMessage());
        }
    }
}
