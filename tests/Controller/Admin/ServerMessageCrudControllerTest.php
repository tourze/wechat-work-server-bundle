<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatWorkServerBundle\Controller\Admin\ServerMessageCrudController;
use WechatWorkServerBundle\Entity\ServerMessage;

/**
 * @internal
 */
#[CoversClass(ServerMessageCrudController::class)]
#[Group('controller')]
#[RunTestsInSeparateProcesses]
final class ServerMessageCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): ServerMessageCrudController
    {
        return self::getService(ServerMessageCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'Corp ID' => ['企业微信CorpID'];
        yield 'Create Time' => ['创建时间'];
        yield 'Message Type' => ['消息类型'];
    }

    /**
     * 提供NEW页面字段，由于NEW操作被禁用，提供虚拟数据以满足框架要求
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        // ServerMessageCrudController禁用了NEW操作（Action::NEW被disable）
        // 提供虚拟字段数据以满足测试框架要求，实际测试将被跳过
        yield 'dummy' => ['dummy'];
    }

    /**
     * 提供EDIT页面字段，由于EDIT操作被禁用，提供虚拟数据以满足框架要求
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        // ServerMessageCrudController禁用了EDIT操作（Action::EDIT被disable）
        // 提供虚拟字段数据以满足测试框架要求，实际测试将被跳过
        yield 'dummy' => ['dummy'];
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(ServerMessage::class, ServerMessageCrudController::getEntityFqcn());
    }

    public function testControllerIsFinal(): void
    {
        $reflection = new \ReflectionClass(ServerMessageCrudController::class);
        $this->assertTrue($reflection->isFinal());
    }

    public function testControllerHasRequiredMethods(): void
    {
        $reflection = new \ReflectionClass(ServerMessageCrudController::class);

        $this->assertTrue($reflection->hasMethod('configureCrud'));
        $this->assertTrue($reflection->hasMethod('configureActions'));
        $this->assertTrue($reflection->hasMethod('configureFields'));
    }

    public function testControllerNamespace(): void
    {
        $reflection = new \ReflectionClass(ServerMessageCrudController::class);
        $this->assertSame('WechatWorkServerBundle\Controller\Admin', $reflection->getNamespaceName());
    }

    public function testControllerIsInstantiable(): void
    {
        $controller = new ServerMessageCrudController();
        $this->assertInstanceOf(ServerMessageCrudController::class, $controller);
    }
}
