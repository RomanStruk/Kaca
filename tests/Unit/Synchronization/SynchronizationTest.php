<?php

namespace Kaca\Tests\Unit\Synchronization;

use Kaca\Database\Factories\ShiftFactory;
use Kaca\Models\Shift;
use Kaca\Models\Synchronization;
use Kaca\Tests\TestCase;

class SynchronizationTest extends TestCase
{
    /** @test */
    public function it_create_sync_record_after_create_model()
    {
        $this->assertDatabaseCount(Synchronization::class, 0);

        $target = ShiftFactory::new()->forOpenedStatus()->create();

        $this->assertDatabaseHas(Synchronization::class, ['target' => $target->id, 'status' => \Kaca\Synchronization::STATUS_CREATED]);
    }

    /** @test */
    public function it_available_after_change_syc_status()
    {
        $target = ShiftFactory::new()->forOpenedStatus()->create();

        \Kaca\Synchronization::begin($target->id);
        $this->assertFalse(\Kaca\Synchronization::isAvailable($target->id));

        \Kaca\Synchronization::failed($target->id);
        $this->assertFalse(\Kaca\Synchronization::isAvailable($target->id));

        \Kaca\Synchronization::finish($target->id);
        $this->assertTrue(\Kaca\Synchronization::isAvailable($target->id));
    }

    /** @test */
    public function it_correct_return_status_for_target()
    {
        $target = ShiftFactory::new()->forOpenedStatus()->create();

        $status = \Kaca\Synchronization::getStatusFor($target->id);
        $this->assertEquals(\Kaca\Synchronization::STATUS_CREATED, $status);

        \Kaca\Synchronization::begin($target->id);
        $status = \Kaca\Synchronization::getStatusFor($target->id);
        $this->assertEquals(\Kaca\Synchronization::STATUS_PROCESSING, $status);

        \Kaca\Synchronization::failed($target->id);
        $status = \Kaca\Synchronization::getStatusFor($target->id);
        $this->assertEquals(\Kaca\Synchronization::STATUS_FAILED, $status);

        \Kaca\Synchronization::finish($target->id);
        $status = \Kaca\Synchronization::getStatusFor($target->id);
        $this->assertEquals(\Kaca\Synchronization::STATUS_DONE, $status);
    }

    /** @test */
    public function it_find_models_for_their_status()
    {
        $target1 = ShiftFactory::new()->forOpenedStatus()->create();
        $target2 = ShiftFactory::new()->forOpenedStatus()->create();

        $this->assertDatabaseCount(Synchronization::class, 2);
        $targets = \Kaca\Synchronization::findWithStatus(Shift::class, \Kaca\Synchronization::STATUS_CREATED);
//        dd($targets->collapse());
//        dd(Synchronization::all()->toArray(), $targets->toArray());
        $this->assertCount(2, $targets);
    }
}