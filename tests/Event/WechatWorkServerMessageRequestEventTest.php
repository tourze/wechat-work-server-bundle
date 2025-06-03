<?php

namespace WechatWorkServerBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use Symfony\Contracts\EventDispatcher\Event;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;

class WechatWorkServerMessageRequestEventTest extends TestCase
{
    public function test_event_creation_success(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        
        $this->assertInstanceOf(Event::class, $event);
        $this->assertInstanceOf(WechatWorkServerMessageRequestEvent::class, $event);
    }

    public function test_event_extends_symfony_event(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $reflection = new \ReflectionClass($event);
        
        $this->assertTrue($reflection->isSubclassOf(Event::class));
    }

    public function test_set_and_get_message_success(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $message = new ServerMessage();
        
        $event->setMessage($message);
        $result = $event->getMessage();
        
        $this->assertSame($message, $result);
        $this->assertInstanceOf(ServerMessage::class, $result);
    }

    public function test_set_message_with_different_instances(): void
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

    public function test_message_property_type_hint(): void
    {
        $reflection = new \ReflectionClass(WechatWorkServerMessageRequestEvent::class);
        $property = $reflection->getProperty('message');
        
        $this->assertTrue($property->isPrivate());
    }

    public function test_getter_and_setter_methods_exist(): void
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