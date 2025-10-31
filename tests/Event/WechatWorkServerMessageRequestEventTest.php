<?php

namespace WechatWorkServerBundle\Tests\Event;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Contracts\EventDispatcher\Event;
use Tourze\PHPUnitSymfonyUnitTest\AbstractEventTestCase;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;

/**
 * @internal
 */
#[CoversClass(WechatWorkServerMessageRequestEvent::class)]
final class WechatWorkServerMessageRequestEventTest extends AbstractEventTestCase
{
    protected function getEventClass(): string
    {
        return WechatWorkServerMessageRequestEvent::class;
    }

    public function testEventCreationSuccess(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();

        $this->assertInstanceOf(Event::class, $event);
        $this->assertInstanceOf(WechatWorkServerMessageRequestEvent::class, $event);
    }

    public function testEventExtendsSymfonyEvent(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $reflection = new \ReflectionClass($event);

        $this->assertTrue($reflection->isSubclassOf(Event::class));
    }

    public function testSetAndGetMessageSuccess(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $message = new ServerMessage();

        $event->setMessage($message);
        $result = $event->getMessage();

        $this->assertSame($message, $result);
        $this->assertInstanceOf(ServerMessage::class, $result);
    }

    public function testSetMessageWithDifferentInstances(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $message1 = new ServerMessage();
        $message2 = new ServerMessage();

        $event->setMessage($message1);
        $this->assertSame($message1, $event->getMessage());

        $event->setMessage($message2);
        $this->assertSame($message2, $event->getMessage());
        $this->assertNotSame($message1, $event->getMessage());
    }

    public function testMessagePropertyTypeHint(): void
    {
        $reflection = new \ReflectionClass(WechatWorkServerMessageRequestEvent::class);
        $property = $reflection->getProperty('message');

        $this->assertTrue($property->isPrivate());
    }

    public function testGetterAndSetterMethodsExist(): void
    {
        $reflection = new \ReflectionClass(WechatWorkServerMessageRequestEvent::class);

        $this->assertTrue($reflection->hasMethod('getMessage'));
        $this->assertTrue($reflection->hasMethod('setMessage'));

        $getMethod = $reflection->getMethod('getMessage');
        $setMethod = $reflection->getMethod('setMessage');

        $this->assertTrue($getMethod->isPublic());
        $this->assertTrue($setMethod->isPublic());
    }
}
