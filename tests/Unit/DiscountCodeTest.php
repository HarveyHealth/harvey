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

        $this->assertEquals(20, $code->discountForSubtotal(100));

        $code->amount = 50;
        $this->assertEquals(50, $code->discountForSubtotal(100));

        $code->amount = 100;
        $this->assertEquals(100, $code->discountForSubtotal(100));
    }

    public function test_it_calculates_dollar_discounts_correctly()
    {
    	$code = new DiscountCode;
        $code->discount_type = 'dollars';
        $code->amount = 20;

        $this->assertEquals(20, $code->discountForSubtotal(100));

        $code->amount = 25;
        $this->assertEquals(25, $code->discountForSubtotal(100));

        $code->amount = 200;
        $this->assertEquals(100, $code->discountForSubtotal(100));
    }
}
