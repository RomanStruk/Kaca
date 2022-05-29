<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Kaca\Actions\Report\GetReportText;
use Kaca\Contracts\Report\CreatesXReports;
use Kaca\Exception\CheckboxExceptions;
use Kaca\Kaca;
use Kaca\Models\Report;

class ReportsController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Index
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(): View
    {
        $this->authorize('viewAny', Report::class);

        $reports = Report::query()->latest()->paginate(15);

        return view('kaca::reports.index', compact('reports'));
    }

    /**
     * Create x report
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException|\Kaca\Contracts\CheckboxExceptions
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Report::class);

        try {
            $report = app(CreatesXReports::class)
                ->create(Kaca::findCashierByCashierUser($request->user()));

            return redirect()->route('kaca.reports.show', $report);
        } catch (CheckboxExceptions $checkboxExceptions) {
            return redirect()->back()->withErrors($checkboxExceptions->getMessage());
        }
    }

    /**
     * Show report with text
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function show(Request $request, Report $report): View
    {
        $this->authorize('view', $report);

        $text = app(GetReportText::class)
            ->get(Kaca::findCashierByCashierUser($request->user()), $report);

        return view('kaca::reports.show', compact('report', 'text'));
    }
}
