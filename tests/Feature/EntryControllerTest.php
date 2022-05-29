<?php

namespace Kaca\Tests\Feature;

use Kaca\CheckboxEntry;
use Kaca\Database\Factories\UserFactory;
use Kaca\Tests\TestCase;
use Kaca\Tests\TestResponses;

class EntryControllerTest extends TestCase
{
    /** @test */
    public function it_index()
    {
        $this->actingAs($user = UserFactory::new()->create());
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$receipt_sell_donne);

        $response = $this->get(route('kaca.entries.index'));
        $response->assertOk();
        $response->assertSeeText('response');
        $response->assertSeeText(TestResponses::$receipt_sell_donne['id']);
    }

    /** @test */
    public function it_filter_entries_by_tag()
    {
        $this->actingAs($user = UserFactory::new()->create());
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$receipt_sell_donne, 'receipt');
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$shift_status_opened, 'shift');

        $response = $this->get(route('kaca.entries.index', ['tag' => 'receipt']));
        $response->assertOk();
        $response->assertSeeText(TestResponses::$receipt_sell_donne['id']);
        $response->assertDontSeeText(TestResponses::$shift_status_opened['id']);
    }

    /** @test */
    public function it_filter_entries_by_search()
    {
        $this->actingAs($user = UserFactory::new()->create());
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$receipt_sell_created, 'receipt');
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$receipt_sell_donne, 'receipt');
        app(CheckboxEntry::class)->createRecord('response', TestResponses::$shift_status_opened, 'shift');

        $response = $this->get(route('kaca.entries.index', ['search' => TestResponses::$receipt_sell_created['id']]));
        $response->assertOk();
        $response->assertSeeText(TestResponses::$receipt_sell_donne['id']);
        $response->assertSeeText(TestResponses::$receipt_sell_created['status']);
        $response->assertSeeText(TestResponses::$receipt_sell_donne['status']);
        $response->assertDontSeeText(TestResponses::$shift_status_opened['id']);
        $this->assertDatabaseCount(\Kaca\Models\CheckboxEntry::class, 3);
    }
}
