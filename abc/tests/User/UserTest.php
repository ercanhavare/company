<?php

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        $this->user = new User();
    }

    public function testThatCanGetUsername()
    {
        $this->user->setUsername("PathTech");
        $this->assertEquals($this->user->getUsername(), "PathTech");
    }

    public function testPositiveGetUserEmail()
    {
        $this->user->setEmail("test@pathtech.com");
        $this->assertEquals($this->user->getEmail(), "test@pathtech.com");
    }

    public function testNegativeGetUserEmail()
    {
        $this->user->setEmail("test@pathtech.com");
        $this->assertNotEquals($this->user->getEmail(), "test@pathtec.com");
    }
}
