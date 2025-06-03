<?php

namespace WechatWorkServerBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use WechatWorkServerBundle\WechatWorkServerBundle;

class WechatWorkServerBundleTest extends TestCase
{
    public function test_bundle_creation_success(): void
    {
        $bundle = new WechatWorkServerBundle();
        
        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertInstanceOf(WechatWorkServerBundle::class, $bundle);
    }

    public function test_bundle_extends_symfony_bundle(): void
    {
        $bundle = new WechatWorkServerBundle();
        $reflection = new \ReflectionClass($bundle);
        
        $this->assertTrue($reflection->isSubclassOf(Bundle::class));
    }

    public function test_bundle_has_correct_namespace(): void
    {
        $bundle = new WechatWorkServerBundle();
        $reflection = new \ReflectionClass($bundle);
        
        $this->assertEquals('WechatWorkServerBundle', $reflection->getNamespaceName());
        $this->assertEquals('WechatWorkServerBundle', $reflection->getShortName());
    }
} 