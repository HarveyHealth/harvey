<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\DiscountCode;

class DiscountCodeTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_it_calculates_percent_discounts_correctly()
    {
        $code = new DiscountCode;
        $code->discount_type = 'percent';
        $code->amount = 20;

        $this->assertEquals(-22, $code->discountForSubtotal(110));

        $code->amount = 50;
        $this->assertEquals(-60, $code->discountForSubtotal(120));

        $code->amount = 100;
        $this->assertEquals(-130, $code->discountForSubtotal(130));
    }

    public function test_it_calculates_dollar_discounts_correctly()
    {
        $code = new DiscountCode;
        $code->discount_type = 'dollars';
        $code->amount = 20;

        $this->assertEquals(-20, $code->discountForSubtotal(110));

        $code->amount = 25;
        $this->assertEquals(-25, $code->discountForSubtotal(110));

        $code->amount = 110;
        $this->assertEquals(-110, $code->discountForSubtotal(110));

        $code->amount = 200;
        $this->assertEquals(-110, $code->discountForSubtotal(110));
    }
}
