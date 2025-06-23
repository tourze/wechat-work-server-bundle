<?php

namespace WechatWorkServerBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Controller\ServerCallbackController;

class ServerCallbackControllerTest extends TestCase
{
    private ServerCallbackController $controller;

    protected function setUp(): void
    {
        // 由于Controller需要复杂的依赖注入，使用mock
        $entityManager = $this->createMock('Doctrine\ORM\EntityManagerInterface');
        $this->controller = new ServerCallbackController($entityManager);
    }

    public function test_controller_creation_success(): void
    {
        $this->assertInstanceOf(ServerCallbackController::class, $this->controller);
    }

    public function test_controller_extends_abstract_controller(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertTrue(
            $reflection->isSubclassOf('Symfony\Bundle\FrameworkBundle\Controller\AbstractController')
        );
    }

    public function test_controller_has_invoke_method(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertTrue($reflection->hasMethod('__invoke'));

        $method = $reflection->getMethod('__invoke');
        $this->assertTrue($method->isPublic());
    }

    public function test_controller_has_parse_message_method(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertTrue($reflection->hasMethod('parseMessage'));

        $method = $reflection->getMethod('parseMessage');
        $this->assertTrue($method->isPrivate());
    }

    public function test_parse_message_with_xml_content(): void
    {
        $xmlContent = '<xml><ToUserName>ww123</ToUserName><CreateTime>123456</CreateTime></xml>';

        // 使用反射测试私有方法
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $xmlContent);
        $this->assertArrayHasKey('ToUserName', $result);
        $this->assertArrayHasKey('CreateTime', $result);
        $this->assertEquals('ww123', $result['ToUserName']);
        $this->assertEquals('123456', $result['CreateTime']);
    }

    public function test_parse_message_with_json_content(): void
    {
        $jsonContent = json_encode(['ToUserName' => 'ww123', 'CreateTime' => 123456]);

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $jsonContent);
        $this->assertArrayHasKey('ToUserName', $result);
        $this->assertArrayHasKey('CreateTime', $result);
        $this->assertEquals('ww123', $result['ToUserName']);
        $this->assertEquals(123456, $result['CreateTime']);
    }

    public function test_parse_message_with_invalid_xml(): void
    {
        $invalidXml = '<xml><ToUserName>ww123</ToUserName>';

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        // 由于底层XML解析库的行为，这里只测试是否抛出异常
        $this->expectException(\Throwable::class);

        $method->invoke($this->controller, $invalidXml);
    }

    public function test_parse_message_with_invalid_json(): void
    {
        $invalidJson = '{"ToUserName": "ww123", "CreateTime":';

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $invalidJson);

        // 无效JSON应该作为数组返回原始字符串
        $this->assertEquals([$invalidJson], $result);
    }

    public function test_parse_message_with_empty_content(): void
    {
        $emptyContent = '';

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $emptyContent);
        $this->assertEquals([''], $result);
    }

    public function test_parse_message_with_plain_text(): void
    {
        $plainText = 'just plain text';

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $plainText);
        $this->assertEquals(['just plain text'], $result);
    }

    public function test_parse_message_with_nested_xml(): void
    {
        $nestedXml = '<xml><ToUserName>ww123</ToUserName><Event>test</Event><ChangeType>add</ChangeType></xml>';

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $nestedXml);
        $this->assertArrayHasKey('ToUserName', $result);
        $this->assertArrayHasKey('Event', $result);
        $this->assertArrayHasKey('ChangeType', $result);
        $this->assertEquals('ww123', $result['ToUserName']);
        $this->assertEquals('test', $result['Event']);
        $this->assertEquals('add', $result['ChangeType']);
    }

    public function test_parse_message_with_complex_json(): void
    {
        $complexJson = json_encode([
            'ToUserName' => 'ww123',
            'Event' => 'test_event',
            'nested' => [
                'key1' => 'value1',
                'key2' => 'value2'
            ]
        ]);

        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('parseMessage');

        $result = $method->invoke($this->controller, $complexJson);
        $this->assertArrayHasKey('ToUserName', $result);
        $this->assertArrayHasKey('Event', $result);
        $this->assertArrayHasKey('nested', $result);
        $this->assertEquals('ww123', $result['ToUserName']);
        $this->assertEquals('test_event', $result['Event']);
    }

    public function test_controller_namespace(): void
    {
        $reflection = new \ReflectionClass($this->controller);

        $this->assertEquals('WechatWorkServerBundle\Controller', $reflection->getNamespaceName());
        $this->assertEquals('ServerCallbackController', $reflection->getShortName());
    }
}