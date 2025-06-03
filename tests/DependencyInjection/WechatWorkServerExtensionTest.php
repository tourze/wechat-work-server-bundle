<?php

namespace WechatWorkServerBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use WechatWorkServerBundle\DependencyInjection\WechatWorkServerExtension;

class WechatWorkServerExtensionTest extends TestCase
{
    private WechatWorkServerExtension $extension;
    private ContainerBuilder $container;

    protected function setUp(): void
    {
        $this->extension = new WechatWorkServerExtension();
        $this->container = new ContainerBuilder();
    }

    public function test_extension_creation_success(): void
    {
        $this->assertInstanceOf(Extension::class, $this->extension);
        $this->assertInstanceOf(WechatWorkServerExtension::class, $this->extension);
    }

    public function test_extension_extends_symfony_extension(): void
    {
        $reflection = new \ReflectionClass($this->extension);
        
        $this->assertTrue($reflection->isSubclassOf(Extension::class));
    }

    public function test_load_method_exists(): void
    {
        $reflection = new \ReflectionClass($this->extension);
        
        $this->assertTrue($reflection->hasMethod('load'));
        
        $method = $reflection->getMethod('load');
        $this->assertTrue($method->isPublic());
    }

    public function test_load_with_empty_configs(): void
    {
        $configs = [];
        
        // 这个测试主要验证不会抛出异常
        $this->extension->load($configs, $this->container);
        
        // 验证容器仍然是有效的
        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function test_load_with_multiple_configs(): void
    {
        $configs = [
            ['config1' => 'value1'],
            ['config2' => 'value2'],
        ];
        
        // 这个测试主要验证不会抛出异常
        $this->extension->load($configs, $this->container);
        
        // 验证容器仍然是有效的
        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function test_load_method_parameters(): void
    {
        $reflection = new \ReflectionClass($this->extension);
        $method = $reflection->getMethod('load');
        $parameters = $method->getParameters();
        
        $this->assertCount(2, $parameters);
        $this->assertEquals('configs', $parameters[0]->getName());
        $this->assertEquals('container', $parameters[1]->getName());
    }

    public function test_extension_namespace(): void
    {
        $reflection = new \ReflectionClass($this->extension);
        
        $this->assertEquals('WechatWorkServerBundle\DependencyInjection', $reflection->getNamespaceName());
        $this->assertEquals('WechatWorkServerExtension', $reflection->getShortName());
    }
} 