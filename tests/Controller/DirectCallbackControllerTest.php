<?php

namespace WechatWorkServerBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Controller\DirectCallbackController;

class DirectCallbackControllerTest extends TestCase
{
    private DirectCallbackController $controller;

    public function test_controller_creation_success(): void
    {
        $this->assertInstanceOf(DirectCallbackController::class, $this->controller);
    }

    public function test_controller_extends_abstract_controller(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertTrue(
            $reflection->isSubclassOf('Symfony\Bundle\FrameworkBundle\Controller\AbstractController')
        );
    }

    public function test_controller_has_invoke_method(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertTrue($reflection->hasMethod('__invoke'));

        $method = $reflection->getMethod('__invoke');
        $this->assertTrue($method->isPublic());
    }

    public function test_controller_namespace(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertEquals('WechatWorkServerBundle\Controller', $reflection->getNamespaceName());
        $this->assertEquals('DirectCallbackController', $reflection->getShortName());
    }

    protected function setUp(): void
    {
        $this->controller = new DirectCallbackController();
    }
}