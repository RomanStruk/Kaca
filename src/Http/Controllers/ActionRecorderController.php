<?php

declare(strict_types=1);

namespace Kaca\Http\Controllers;

use Illuminate\View\View;
use Kaca\Models\Action;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ActionRecorderController extends BaseController
{
    use AuthorizesRequests;

    /**
     * View latest user actions
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function __invoke(Request $request): View
    {
        $this->authorize('developerPermission');

        $actions = Action::query()->latest()->paginate(15);
        return view('kaca::action-recorders.index', compact('actions'));
    }
}
