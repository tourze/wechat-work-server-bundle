<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;
use WechatWorkServerBundle\DependencyInjection\WechatWorkServerExtension;

/**
 * @internal
 */
#[CoversClass(WechatWorkServerExtension::class)]
final class WechatWorkServerExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function getExtensionClass(): string
    {
        return WechatWorkServerExtension::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMinimalConfiguration(): array
    {
        return [];
    }

    public function testExtensionCreationSuccess(): void
    {
        $extension = new WechatWorkServerExtension();
        $this->assertInstanceOf(Extension::class, $extension);
        $this->assertInstanceOf(WechatWorkServerExtension::class, $extension);
    }

    public function testExtensionExtendsSymfonyExtension(): void
    {
        $extension = new WechatWorkServerExtension();
        $reflection = new \ReflectionClass($extension);

        $this->assertTrue($reflection->isSubclassOf(Extension::class));
    }

    public function testLoadMethodExists(): void
    {
        $extension = new WechatWorkServerExtension();
        $reflection = new \ReflectionClass($extension);

        $this->assertTrue($reflection->hasMethod('load'));

        $method = $reflection->getMethod('load');
        $this->assertTrue($method->isPublic());
    }

    public function testLoadWithEmptyConfigs(): void
    {
        $configs = [];
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new WechatWorkServerExtension();

        // 这个测试主要验证不会抛出异常
        $extension->load($configs, $container);

        // 验证容器仍然是有效的
        $this->assertInstanceOf(ContainerBuilder::class, $container);
    }

    public function testLoadWithMultipleConfigs(): void
    {
        $configs = [
            ['config1' => 'value1'],
            ['config2' => 'value2'],
        ];
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');
        $extension = new WechatWorkServerExtension();

        // 这个测试主要验证不会抛出异常
        $extension->load($configs, $container);

        // 验证容器仍然是有效的
        $this->assertInstanceOf(ContainerBuilder::class, $container);
    }

    public function testLoadMethodParameters(): void
    {
        $extension = new WechatWorkServerExtension();
        $reflection = new \ReflectionClass($extension);
        $method = $reflection->getMethod('load');
        $parameters = $method->getParameters();

        $this->assertCount(2, $parameters);
        $this->assertEquals('configs', $parameters[0]->getName());
        $this->assertEquals('container', $parameters[1]->getName());
    }

    public function testExtensionNamespace(): void
    {
        $extension = new WechatWorkServerExtension();
        $reflection = new \ReflectionClass($extension);

        $this->assertEquals('WechatWorkServerBundle\DependencyInjection', $reflection->getNamespaceName());
        $this->assertEquals('WechatWorkServerExtension', $reflection->getShortName());
    }
}
