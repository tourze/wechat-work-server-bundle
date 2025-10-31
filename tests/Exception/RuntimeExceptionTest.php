<?php

namespace WechatWorkServerBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatWorkServerBundle\Exception\RuntimeException;

/**
 * @internal
 */
#[CoversClass(RuntimeException::class)]
final class RuntimeExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionCreationWithMessage(): void
    {
        $message = '测试异常消息';
        $exception = new RuntimeException($message);

        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }

    public function testExceptionCreationWithMessageAndCode(): void
    {
        $message = '测试异常消息';
        $code = 500;
        $exception = new RuntimeException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function testExceptionCreationWithPreviousException(): void
    {
        $message = '测试异常消息';
        $code = 500;
        $previous = new \Exception('前一个异常');
        $exception = new RuntimeException($message, $code, $previous);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionExtendsRuntimeException(): void
    {
        $exception = new RuntimeException('测试');
        $reflection = new \ReflectionClass($exception);

        $this->assertTrue($reflection->isSubclassOf(\RuntimeException::class));
    }

    public function testExceptionCreationWithEmptyMessage(): void
    {
        $exception = new RuntimeException('');

        $this->assertEquals('', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }
}
