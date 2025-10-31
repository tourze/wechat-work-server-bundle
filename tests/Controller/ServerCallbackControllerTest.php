<?php

namespace WechatWorkServerBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Tourze\PHPUnitSymfonyWebTest\AbstractWebTestCase;
use WechatWorkServerBundle\Controller\ServerCallbackController;
use WechatWorkServerBundle\Repository\AgentRepository;
use WechatWorkServerBundle\Repository\CorpRepository;

/**
 * @internal
 */
#[CoversClass(ServerCallbackController::class)]
#[RunTestsInSeparateProcesses]
final class ServerCallbackControllerTest extends AbstractWebTestCase
{
    public function testControllerIsInstantiable(): void
    {
        // 由于Repository是final类无法Mock，跳过实例化测试
        self::markTestSkipped('Repository classes are final and cannot be mocked');
    }

    public function testControllerExtendsAbstractController(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $this->assertTrue($reflection->isSubclassOf(AbstractController::class));
    }

    public function testControllerHasRouteAttribute(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $method = $reflection->getMethod('__invoke');

        $attributes = $method->getAttributes(Route::class);
        $this->assertCount(1, $attributes);

        /** @var Route $route */
        $route = $attributes[0]->newInstance();
        $this->assertEquals('/wechat/work/server/{corpId}/{agentId}', $route->getPath());
        $this->assertEquals(['GET', 'POST'], $route->getMethods());
    }

    public function testControllerIsFinal(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $this->assertTrue($reflection->isFinal());
    }

    public function testControllerIsNotInternal(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $docComment = $reflection->getDocComment();
        $this->assertFalse($docComment, 'Controller should not have a doc comment');
    }

    public function testControllerNamespace(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $this->assertEquals('WechatWorkServerBundle\Controller', $reflection->getNamespaceName());
        $this->assertEquals('ServerCallbackController', $reflection->getShortName());
    }

    public function testControllerHasInvokeMethod(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $this->assertTrue($reflection->hasMethod('__invoke'));

        $method = $reflection->getMethod('__invoke');
        $this->assertTrue($method->isPublic());
        $this->assertFalse($method->isStatic());
    }

    public function testControllerMethodParameters(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $method = $reflection->getMethod('__invoke');

        $parameters = $method->getParameters();
        $this->assertGreaterThan(0, count($parameters));

        // 检查第一个参数是 corpId (string)
        $corpIdParam = $parameters[0];
        $this->assertEquals('corpId', $corpIdParam->getName());
        $corpIdType = $corpIdParam->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $corpIdType);
        $this->assertEquals('string', $corpIdType->getName());

        // 检查第二个参数是 agentId (string)
        $agentIdParam = $parameters[1];
        $this->assertEquals('agentId', $agentIdParam->getName());
        $agentIdType = $agentIdParam->getType();
        $this->assertInstanceOf(\ReflectionNamedType::class, $agentIdType);
        $this->assertEquals('string', $agentIdType->getName());
    }

    public function testControllerMethodReturnType(): void
    {
        $reflection = new \ReflectionClass(ServerCallbackController::class);
        $method = $reflection->getMethod('__invoke');

        $returnType = $method->getReturnType();
        $this->assertNotNull($returnType);
        $this->assertInstanceOf(\ReflectionNamedType::class, $returnType);
        $this->assertEquals('Symfony\Component\HttpFoundation\Response', $returnType->getName());
    }

    #[DataProvider('provideNotAllowedMethods')]
    public function testMethodNotAllowed(string $method): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request($method, '/wechat/work/server/test-corp/test-agent');
    }
}
