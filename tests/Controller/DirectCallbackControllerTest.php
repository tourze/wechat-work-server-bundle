<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Controller;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Tourze\PHPUnitSymfonyWebTest\AbstractWebTestCase;
use WechatWorkServerBundle\Controller\DirectCallbackController;

/**
 * @internal
 */
#[CoversClass(DirectCallbackController::class)]
#[RunTestsInSeparateProcesses]
final class DirectCallbackControllerTest extends AbstractWebTestCase
{
    public function testDirectCallbackRouteExistsAndResponds(): void
    {
        $client = self::createClientWithDatabase();

        $client->request('POST', '/wechat/work/direct-server/test-corp-id', [], [], [], '<xml>test</xml>');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), 'Expected successful response but got: ' . $response->getStatusCode());
        $this->assertSame('success', $response->getContent());
    }

    public function testDirectCallbackWithGetRequest(): void
    {
        $client = self::createClientWithDatabase();

        $client->request('GET', '/wechat/work/direct-server/test-corp-id', [], [], [], '<xml>test</xml>');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), 'Expected successful response but got: ' . $response->getStatusCode());
        $this->assertSame('success', $response->getContent());
    }

    public function testDirectCallbackSanitizesCorpId(): void
    {
        $client = self::createClientWithDatabase();

        $client->request('POST', '/wechat/work/direct-server/test..malicious..corp', [], [], [], '<xml>test</xml>');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), 'Expected successful response but got: ' . $response->getStatusCode());
    }

    public function testDirectCallbackLogsRequest(): void
    {
        $client = self::createClientWithDatabase();
        $kernel = $client->getKernel();

        $corpId = 'test-corp-logs';
        $testContent = '<xml><ToUserName>test</ToUserName></xml>';
        $logFile = $kernel->getProjectDir() . "/wechat-work-{$corpId}.log";

        if (file_exists($logFile)) {
            unlink($logFile);
        }

        $client->request('POST', "/wechat/work/direct-server/{$corpId}", [], [], [], $testContent);

        $this->assertFileExists($logFile);
        $logContent = file_get_contents($logFile);
        $this->assertIsString($logContent);
        $this->assertStringContainsString($testContent, $logContent);

        if (file_exists($logFile)) {
            unlink($logFile);
        }
    }

    public function testDirectCallbackUnauthenticatedAccess(): void
    {
        $client = self::createClientWithDatabase();

        $client->request('POST', '/wechat/work/direct-server/unauthenticated-corp', [], [], [], '<xml>test</xml>');

        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), 'Expected successful response but got: ' . $response->getStatusCode());
    }

    public function testDirectCallbackWithPutMethod(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request('PUT', '/wechat/work/direct-server/test-corp-id', [], [], [], '<xml>test</xml>');
    }

    public function testDirectCallbackWithDeleteMethod(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request('DELETE', '/wechat/work/direct-server/test-corp-id');
    }

    public function testDirectCallbackWithPatchMethod(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request('PATCH', '/wechat/work/direct-server/test-corp-id', [], [], [], '<xml>test</xml>');
    }

    public function testDirectCallbackWithHeadMethod(): void
    {
        $client = self::createClientWithDatabase();

        $client->request('HEAD', '/wechat/work/direct-server/test-corp-id', [], [], [], '<xml>test</xml>');

        // HEAD 方法在 Symfony 中会自动转换为 GET，所以应该返回成功
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful(), 'Expected successful response but got: ' . $response->getStatusCode());
    }

    public function testDirectCallbackWithOptionsMethod(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request('OPTIONS', '/wechat/work/direct-server/test-corp-id');
    }

    #[DataProvider('provideNotAllowedMethods')]
    public function testMethodNotAllowed(string $method): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(false);

        $this->expectException(MethodNotAllowedHttpException::class);
        $client->request($method, '/wechat/work/direct-server/test-corp-id');
    }
}
