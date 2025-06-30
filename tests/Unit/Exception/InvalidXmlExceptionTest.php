<?php

namespace WechatWorkServerBundle\Tests\Unit\Exception;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Exception\InvalidXmlException;

class InvalidXmlExceptionTest extends TestCase
{
    public function test_exception_can_be_instantiated(): void
    {
        $exception = new InvalidXmlException();
        $this->assertInstanceOf(InvalidXmlException::class, $exception);
    }

    public function test_exception_extends_invalid_argument_exception(): void
    {
        $exception = new InvalidXmlException();
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
    }

    public function test_exception_with_message(): void
    {
        $message = 'Invalid XML format';
        $exception = new InvalidXmlException($message);
        
        $this->assertEquals($message, $exception->getMessage());
    }

    public function test_exception_with_code(): void
    {
        $message = 'Invalid XML';
        $code = 400;
        $exception = new InvalidXmlException($message, $code);
        
        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function test_exception_with_previous(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new InvalidXmlException('Invalid XML', 0, $previous);
        
        $this->assertSame($previous, $exception->getPrevious());
    }

    public function test_exception_is_throwable(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('XML parsing failed');
        
        throw new InvalidXmlException('XML parsing failed');
    }

    public function test_exception_inheritance_chain(): void
    {
        $exception = new InvalidXmlException();
        
        $this->assertInstanceOf(\Throwable::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(InvalidXmlException::class, $exception);
    }
}