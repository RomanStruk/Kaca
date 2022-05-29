<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Kaca\Contracts\CheckboxEntries;
use function view;

class EntryController extends BaseController
{
    use AuthorizesRequests;

    /**
     * Display a listing of the entries.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request): View
    {
        $this->authorize('developerPermission');

        $entries = app(CheckboxEntries::class)
            ->paginateWith(
                $request->get('tag'),
                $request->get('search')
            );
        return view('kaca::entries.index', compact('entries'));
    }
}
