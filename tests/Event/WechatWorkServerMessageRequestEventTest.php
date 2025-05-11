<?php

namespace WechatWorkServerBundle\Tests\Event;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;

class WechatWorkServerMessageRequestEventTest extends TestCase
{
    public function testGetSetMessage(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $message = new ServerMessage();
        
        $message->setToUserName('ww72805907153f7fa3');
        $message->setCreateTime(1654355183);
        
        $event->setMessage($message);
        
        $this->assertSame($message, $event->getMessage());
    }
    
    public function testEventIsPropagationStopped(): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        
        $this->assertFalse($event->isPropagationStopped());
        
        $event->stopPropagation();
        
        $this->assertTrue($event->isPropagationStopped());
    }
}
