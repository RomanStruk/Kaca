<?php

namespace Kaca\Tests\Feature;

use Kaca\ActionRecorder;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\ShiftFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Models\Action;
use Kaca\Models\Receipt;
use Kaca\Models\Shift;
use Kaca\Tests\TestCase;

class ActionRecorderControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $this->actingAs($user = UserFactory::new()->create());
        $shift = ShiftFactory::new()->create();
        $receipt = ReceiptFactory::new()->create();

        ActionRecorder::creating($user, Shift::class, $shift->id);
        ActionRecorder::creating($user, Receipt::class, $receipt->id);

        $response = $this->get(route('kaca.action-recorders.index'));
        $response->assertSeeText('ShiftCreate');
        $response->assertSeeText('ReceiptCreate');
        $response->assertSeeText($user->{Kaca::$userFieldName});
    }
}