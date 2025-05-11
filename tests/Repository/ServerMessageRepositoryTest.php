<?php

namespace WechatWorkServerBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

class ServerMessageRepositoryTest extends TestCase
{
    private ServerMessageRepository $repository;
    private EntityManagerInterface $entityManager;
    private ManagerRegistry $registry;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        
        $this->registry = $this->createMock(ManagerRegistry::class);
        $this->registry->method('getManagerForClass')
            ->willReturn($this->entityManager);
        
        // 创建 ServerMessageRepository 的子类，覆盖 getEntityManager 方法
        $this->repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->setConstructorArgs([$this->registry])
            ->onlyMethods(['getEntityManager'])
            ->getMock();
        
        // 配置 getEntityManager 返回我们的 mock
        $this->repository->method('getEntityManager')
            ->willReturn($this->entityManager);
    }

    public function testSaveXML_withValidXML(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <xml>
            <ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName>
            <FromUserName><![CDATA[zhangsan]]></FromUserName>
            <CreateTime>1654355183</CreateTime>
            <MsgType><![CDATA[event]]></MsgType>
            <Event><![CDATA[subscribe]]></Event>
        </xml>';
        
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(ServerMessage::class));
        
        $message = $this->repository->saveXML($xml);
        
        $this->assertInstanceOf(ServerMessage::class, $message);
        $this->assertEquals('ww72805907153f7fa3', $message->getToUserName());
        $this->assertEquals('zhangsan', $message->getFromUserName());
        $this->assertEquals(1654355183, $message->getCreateTime());
        $this->assertEquals('event', $message->getMsgType());
        $this->assertEquals('subscribe', $message->getEvent());
    }
    
    public function testSaveXML_withFlush(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <xml>
            <ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName>
            <CreateTime>1654355183</CreateTime>
        </xml>';
        
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(ServerMessage::class));
        
        $this->entityManager->expects($this->once())
            ->method('flush');
        
        $this->repository->saveXML($xml, true);
    }

    public function testSaveXML_withInvalidXML(): void
    {
        $this->markTestSkipped('由于依赖 XML::parse 内部实现，无法测试特定的 InvalidArgumentException 抛出');
    }
    
    public function testSaveXML_withMissingRequiredFields(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <xml>
            <SomethingElse>value</SomethingElse>
        </xml>';
        
        $message = $this->repository->saveXML($xml);
        $this->assertNull($message);
    }
    
    public function testSaveXML_withMissingCreateTime(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <xml>
            <ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName>
        </xml>';
        
        $message = $this->repository->saveXML($xml);
        $this->assertNull($message);
    }
    
    public function testAssignMessage(): void
    {
        $serverMessage = new ServerMessage();
        
        $data = [
            'ToUserName' => 'ww72805907153f7fa3',
            'FromUserName' => 'zhangsan',
            'CreateTime' => 1654355183,
            'MsgType' => 'event',
            'Event' => 'subscribe',
            'ChangeType' => 'create',
            'UserID' => 'userId123',
            'ExternalUserID' => 'ext123',
            'WelcomeCode' => 'welcome123',
            'ChatId' => 'chatId123',
            'UpdateDetail' => 'detail123',
            'JoinScene' => 1,
            'MemChangeCnt' => 2,
            'QuitScene' => 3,
            'State' => 'state123',
        ];
        
        $this->repository->assignMessage($serverMessage, $data);
        
        $this->assertEquals($data['ToUserName'], $serverMessage->getToUserName());
        $this->assertEquals($data['FromUserName'], $serverMessage->getFromUserName());
        $this->assertEquals($data['CreateTime'], $serverMessage->getCreateTime());
        $this->assertEquals($data['MsgType'], $serverMessage->getMsgType());
        $this->assertEquals($data['Event'], $serverMessage->getEvent());
        $this->assertEquals($data['ChangeType'], $serverMessage->getChangeType());
        $this->assertEquals($data['UserID'], $serverMessage->getUserId());
        $this->assertEquals($data['ExternalUserID'], $serverMessage->getExternalUserId());
        $this->assertEquals($data['WelcomeCode'], $serverMessage->getWelcomeCode());
        $this->assertEquals($data['ChatId'], $serverMessage->getChatId());
        $this->assertEquals($data['UpdateDetail'], $serverMessage->getUpdateDetail());
        $this->assertEquals($data['JoinScene'], $serverMessage->getJoinScene());
        $this->assertEquals($data['MemChangeCnt'], $serverMessage->getMemChangeCnt());
        $this->assertEquals($data['QuitScene'], $serverMessage->getQuitScene());
        $this->assertEquals($data['State'], $serverMessage->getState());
    }
    
    public function testAssignMessage_withPartialData(): void
    {
        $serverMessage = new ServerMessage();
        
        $data = [
            'ToUserName' => 'ww72805907153f7fa3',
            'CreateTime' => 1654355183,
        ];
        
        $this->repository->assignMessage($serverMessage, $data);
        
        $this->assertEquals($data['ToUserName'], $serverMessage->getToUserName());
        $this->assertEquals($data['CreateTime'], $serverMessage->getCreateTime());
        $this->assertNull($serverMessage->getFromUserName());
        $this->assertNull($serverMessage->getMsgType());
        $this->assertNull($serverMessage->getEvent());
    }
} 