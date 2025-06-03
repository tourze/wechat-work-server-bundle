<?php

namespace WechatWorkServerBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Exception\RuntimeException;

class RuntimeExceptionTest extends TestCase
{
    public function test_exception_creation_with_message(): void
    {
        $message = '测试异常消息';
        $exception = new RuntimeException($message);
        
        $this->assertInstanceOf(\RuntimeException::class, $exception);
        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }

    public function test_exception_creation_with_message_and_code(): void
    {
        $message = '测试异常消息';
        $code = 500;
        $exception = new RuntimeException($message, $code);
        
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function test_exception_creation_with_previous_exception(): void
    {
        $message = '测试异常消息';
        $code = 500;
        $previous = new \Exception('前一个异常');
        $exception = new RuntimeException($message, $code, $previous);
        
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function test_exception_extends_runtime_exception(): void
    {
        $exception = new RuntimeException('测试');
        $reflection = new \ReflectionClass($exception);
        
        $this->assertTrue($reflection->isSubclassOf(\RuntimeException::class));
    }

    public function test_exception_creation_with_empty_message(): void
    {
        $exception = new RuntimeException('');
        
        $this->assertEquals('', $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
    }
} 