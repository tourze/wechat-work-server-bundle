<?php

namespace WechatWorkServerBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;
use WechatWorkServerBundle\Entity\ServerMessage;

/**
 * 企业微信服务端通知
 */
class WechatWorkServerMessageRequestEvent extends Event
{
    /**
     * @var ServerMessage 发送的消息
     */
    private ServerMessage $message;

    public function getMessage(): ServerMessage
    {
        return $this->message;
    }

    public function setMessage(ServerMessage $message): void
    {
        $this->message = $message;
    }
}
