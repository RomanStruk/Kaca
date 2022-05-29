<?php

namespace Kaca\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Kaca\Contracts\Shift\OpenShifts;
use Kaca\Database\Factories\CashierFactory;
use Kaca\Database\Factories\CashRegisterFactory;
use Kaca\Models\CheckboxEntry;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestModels\User;
use Kaca\Tests\TestResponses;

class CheckboxEntryTest extends TestCase
{
    /** @test */
    public function it_create_record()
    {
        $cashRegister = CashRegisterFactory::new()->create();
        $cashier = CashierFactory::new()->create();
        $this->actingAs($user = User::factory(['cashier_id' => $cashier->id, 'cash_register_id' => $cashRegister->id])->create());

        // підмінити відповідь для сервісу
        Http::fake([
            '*/api/v1/shifts' => Http::response(TestResponses::$shift_status_created),
            '*/api/v1/shifts/*' => Http::response(TestResponses::$shift_status_opened),
            '*' => Http::response('', 200, []),
        ]);

        app(OpenShifts::class)->open($user, '8d4471ff-726c-4ec7-bfda-73f6a048c6a2');

        $this->assertDatabaseCount(CheckboxEntry::class, 2);
    }
}
