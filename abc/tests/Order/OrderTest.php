<?php

use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testAdd()
    {
        $result = 5 + 9;
        $this->assertEquals(14, $result);
    }
}