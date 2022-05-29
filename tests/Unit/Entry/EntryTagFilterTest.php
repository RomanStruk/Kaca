<?php

namespace Kaca\Tests\Unit\Entry;

use Kaca\CheckboxEntry;
use Kaca\Database\Factories\ReceiptFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class EntryTagFilterTest extends TestCase
{

    /** @test */
    public function it_filter_entries_by_object_tag()
    {
        $this->actingAs($user = UserFactory::new()->create());
        $receipt = ReceiptFactory::new(['id' => TestResponses::$receipt_sell_donne['id']])->create();
        app(CheckboxEntry::class)
            ->createRecord('response', TestResponses::$receipt_sell_donne, 'receipt:' . TestResponses::$receipt_sell_donne['id']);
        app(CheckboxEntry::class)
            ->createRecord('response', TestResponses::$shift_status_opened, 'shift');

        $entries = app(CheckboxEntry::class)->paginateWith($receipt);
        $this->assertCount(1, $entries);
        $this->assertDatabaseCount(\Kaca\Models\CheckboxEntry::class, 2);
    }
}