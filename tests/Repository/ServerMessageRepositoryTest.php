<?php

namespace WechatWorkServerBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Exception\InvalidXmlException;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

/**
 * @internal
 */
#[CoversClass(ServerMessageRepository::class)]
#[RunTestsInSeparateProcesses]
final class ServerMessageRepositoryTest extends AbstractRepositoryTestCase
{
    private ServerMessageRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(ServerMessageRepository::class);
    }

    protected function createNewEntity(): object
    {
        $message = new ServerMessage();
        $message->setToUserName('test_corp_' . uniqid());
        $message->setCreateTime(time());

        return $message;
    }

    protected function getRepository(): ServerMessageRepository
    {
        return self::getService(ServerMessageRepository::class);
    }

    public function testCreateFromXMLWithValidXml(): void
    {
        $xml = '<xml><CreateTime>1234567890</CreateTime><ToUserName>test_corp</ToUserName><FromUserName>test_user</FromUserName></xml>';

        $message = $this->repository->createFromXML($xml);

        $this->assertInstanceOf(ServerMessage::class, $message);
        $this->assertEquals(1234567890, $message->getCreateTime());
        $this->assertEquals('test_corp', $message->getToUserName());
        $this->assertEquals('test_user', $message->getFromUserName());
    }

    public function testCreateFromXMLWithEmptyXmlThrowsException(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('xml解析为空');

        $this->repository->createFromXML('');
    }

    public function testCreateFromXMLWithInvalidXmlThrowsException(): void
    {
        $this->expectException(InvalidXmlException::class);
        $this->expectExceptionMessage('xml解析为空');

        $this->repository->createFromXML('<invalid>xml');
    }

    public function testCreateFromXMLWithMissingCreateTimeReturnsNull(): void
    {
        $xml = '<xml><ToUserName>test_corp</ToUserName><FromUserName>test_user</FromUserName></xml>';

        $message = $this->repository->createFromXML($xml);

        $this->assertNull($message);
    }

    public function testCreateFromXMLWithMissingToUserNameReturnsNull(): void
    {
        $xml = '<xml><CreateTime>1234567890</CreateTime><FromUserName>test_user</FromUserName></xml>';

        $message = $this->repository->createFromXML($xml);

        $this->assertNull($message);
    }

    public function testAssignMessage(): void
    {
        $message = new ServerMessage();
        $data = [
            'CreateTime' => 1234567890,
            'ToUserName' => 'new_corp',
            'FromUserName' => 'new_user',
            'MsgType' => 'text',
            'Event' => 'user_add',
        ];

        $this->repository->assignMessage($message, $data);

        $this->assertEquals(1234567890, $message->getCreateTime());
        $this->assertEquals('new_corp', $message->getToUserName());
        $this->assertEquals('new_user', $message->getFromUserName());
        $this->assertEquals('text', $message->getMsgType());
        $this->assertEquals('user_add', $message->getEvent());
    }
}
