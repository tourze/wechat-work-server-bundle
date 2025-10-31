<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;
use WechatWorkServerBundle\Service\AdminMenu;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[Group('service')]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    protected function onSetUp(): void
    {
    }

    public function testServiceIsReadonly(): void
    {
        $reflection = new \ReflectionClass(AdminMenu::class);
        $this->assertTrue($reflection->isReadOnly());
    }

    public function testServiceImplementsMenuProviderInterface(): void
    {
        $reflection = new \ReflectionClass(AdminMenu::class);
        $this->assertTrue($reflection->implementsInterface(MenuProviderInterface::class));
    }

    public function testInvokeMethodExists(): void
    {
        $reflection = new \ReflectionClass(AdminMenu::class);
        $this->assertTrue($reflection->hasMethod('__invoke'));
    }

    public function testServiceNamespace(): void
    {
        $reflection = new \ReflectionClass(AdminMenu::class);
        $this->assertSame('WechatWorkServerBundle\Service', $reflection->getNamespaceName());
    }
}
