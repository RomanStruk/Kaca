<?php

namespace Kaca\Tests\Unit\Report;

use Illuminate\Support\Facades\Http;
use Kaca\Actions\Report\GetReportText;
use Kaca\Database\Factories\ReportFactory;
use Kaca\Database\Factories\UserFactory;
use Kaca\Kaca;
use Kaca\Tests\TestResponses;

class GetReportTextTest extends \Kaca\Tests\TestCase
{
    /** @test */
    public function it_get_report_from_service_as_text()
    {
        $user = UserFactory::new()->withCashier()->create();
        $this->actingAs($user);
        $report = ReportFactory::new(['serial' => 1])->create();

        Http::fake([
            '*/api/v1/reports/*' => Http::response(TestResponses::$x_report_as_text, 200, ['Content-Type' => 'text/plain; charset=utf-8']),
            '*' => Http::response('', 200, []),
        ]);

        $text = app(GetReportText::class)->get(Kaca::findCashierByCashierUser($user), $report);

        $this->assertEquals($text, TestResponses::$x_report_as_text);
    }
}