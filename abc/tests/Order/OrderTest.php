<?php

use App\Entity\Order;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    protected $order;

    public function setUp()
    {
        $this->order = new Order();
    }

    public function testThatCanGetTheOrderCode()
    {
        $this->order->setOrderCode("PathTech2020");

        $this->assertEquals($this->order->getOrderCode(), "PathTech2020");
    }

    public function testNegativeOrderCodeForClassHasAttribute()
    {
        $this->assertClassNotHasAttribute("order_code", Order::class, "Order class doesn't has order_code as attribute");
    }

    public function testPositiveOrderCodeForClassHasAttribute()
    {
        $this->assertClassHasAttribute("orderCode", Order::class, "Order class doesn't has orderCode as attribute");
    }
}