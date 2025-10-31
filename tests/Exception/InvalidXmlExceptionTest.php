<?php

namespace WechatWorkServerBundle\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use WechatWorkServerBundle\Exception\InvalidXmlException;

/**
 * @internal
 */
#[CoversClass(InvalidXmlException::class)]
final class InvalidXmlExceptionTest extends AbstractExceptionTestCase
{
    public function testExceptionCanBeInstantiated(): void
    {
        $exception = new InvalidXmlException();
        $this->assertInstanceOf(InvalidXmlException::class, $exception);
    }

    public function testExceptionExtendsInvalidArgumentException(): void
    {
        $exception = new InvalidXmlException();
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
    }

    public function testExceptionWithMessage(): void
    {
        $message = 'Invalid XML format';
        $exception = new InvalidXmlException($message);

        $this->assertEquals($message, $exception->getMessage());
    }

    public function testExceptionWithCode(): void
    {
        $message = 'Invalid XML';
        $code = 400;
        $exception = new InvalidXmlException($message, $code);

        $this->assertEquals($message, $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
    }

    public function testExceptionWithPrevious(): void
    {
        $previous = new \Exception('Previous exception');
        $exception = new InvalidXmlException('Invalid XML', 0, $previous);

        $this->assertSame($previous, $exception->getPrevious());
    }

    public function testExceptionIsThrowable(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('XML parsing failed');

        throw new InvalidXmlException('XML parsing failed');
    }

    public function testExceptionInheritanceChain(): void
    {
        $exception = new InvalidXmlException();

        $this->assertInstanceOf(\Throwable::class, $exception);
        $this->assertInstanceOf(\Exception::class, $exception);
        $this->assertInstanceOf(\InvalidArgumentException::class, $exception);
        $this->assertInstanceOf(InvalidXmlException::class, $exception);
    }
}
