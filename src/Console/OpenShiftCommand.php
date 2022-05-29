<?php

declare(strict_types=1);

namespace Kaca\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Kaca;

class OpenShiftCommand extends Command
{
    protected $signature = 'kaca:open-shift {user : Cashier User Id}';

    protected $description = 'Open Shift';

    public function handle(OpenShifts $openShifts)
    {
        $user = Kaca::findUserByIdOrFail(intval($this->argument('user')));

        if (Gate::forUser($user)->allows('open', Kaca::findShiftByCashierUser($user))) {
            $openShifts->open($user, Str::uuid()->toString());
        }
    }
}
