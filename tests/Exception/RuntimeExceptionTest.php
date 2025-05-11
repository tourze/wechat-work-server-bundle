<?php

namespace WechatWorkServerBundle\Tests\Exception;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Exception\RuntimeException;

class RuntimeExceptionTest extends TestCase
{
    public function testExceptionCanBeThrown(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Test exception message');
        
        throw new RuntimeException('Test exception message');
    }
    
    public function testExceptionInheritsRuntimeException(): void
    {
        $exception = new RuntimeException();
        
        $this->assertInstanceOf(\RuntimeException::class, $exception);
    }
    
    public function testExceptionWithCodeAndPrevious(): void
    {
        $code = 123;
        $previousException = new \Exception('Previous exception');
        $exception = new RuntimeException('Test exception', $code, $previousException);
        
        $this->assertEquals('Test exception', $exception->getMessage());
        $this->assertEquals($code, $exception->getCode());
        $this->assertSame($previousException, $exception->getPrevious());
    }
} 