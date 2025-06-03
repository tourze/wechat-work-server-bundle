<?php

namespace WechatWorkServerBundle\Tests\Repository;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

class ServerMessageRepositoryTest extends TestCase
{
    public function test_repository_can_be_instantiated(): void
    {
        // 由于Repository需要复杂的Doctrine配置，这里只测试基本方法
        $this->assertTrue(class_exists(ServerMessageRepository::class));
    }

    public function test_assign_message_with_complete_data(): void
    {
        $message = new ServerMessage();
        $data = [
            'CreateTime' => 1654355183,
            'ToUserName' => 'ww72805907153f7fa3',
            'FromUserName' => 'test_user',
            'MsgType' => 'event',
            'Event' => 'kf_msg_or_event',
            'ChangeType' => 'add_external_contact',
            'UserID' => 'user123',
            'ExternalUserID' => 'external123',
            'WelcomeCode' => 'welcome123',
            'ChatId' => 'chat123',
            'UpdateDetail' => 'update_detail',
            'JoinScene' => 1,
            'MemChangeCnt' => 5,
            'QuitScene' => 2,
            'State' => 'test_state',
        ];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        // 创建mock repository
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertEquals(1654355183, $message->getCreateTime());
        $this->assertEquals('ww72805907153f7fa3', $message->getToUserName());
        $this->assertEquals('test_user', $message->getFromUserName());
        $this->assertEquals('event', $message->getMsgType());
        $this->assertEquals('kf_msg_or_event', $message->getEvent());
        $this->assertEquals('add_external_contact', $message->getChangeType());
        $this->assertEquals('user123', $message->getUserId());
        $this->assertEquals('external123', $message->getExternalUserId());
        $this->assertEquals('welcome123', $message->getWelcomeCode());
        $this->assertEquals('chat123', $message->getChatId());
        $this->assertEquals('update_detail', $message->getUpdateDetail());
        $this->assertEquals(1, $message->getJoinScene());
        $this->assertEquals(5, $message->getMemChangeCnt());
        $this->assertEquals(2, $message->getQuitScene());
        $this->assertEquals('test_state', $message->getState());
    }

    public function test_assign_message_with_partial_data(): void
    {
        $message = new ServerMessage();
        $data = [
            'CreateTime' => 1654355183,
            'ToUserName' => 'ww72805907153f7fa3',
            'MsgType' => 'event',
        ];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertEquals(1654355183, $message->getCreateTime());
        $this->assertEquals('ww72805907153f7fa3', $message->getToUserName());
        $this->assertEquals('event', $message->getMsgType());
        $this->assertNull($message->getFromUserName());
        $this->assertNull($message->getEvent());
    }

    public function test_assign_message_with_empty_data(): void
    {
        $message = new ServerMessage();
        $data = [];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertNull($message->getCreateTime());
        $this->assertNull($message->getToUserName());
        $this->assertNull($message->getFromUserName());
    }

    public function test_assign_message_with_numeric_values(): void
    {
        $message = new ServerMessage();
        $data = [
            'JoinScene' => 0,
            'MemChangeCnt' => 10,
            'QuitScene' => 3,
        ];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertEquals(0, $message->getJoinScene());
        $this->assertEquals(10, $message->getMemChangeCnt());
        $this->assertEquals(3, $message->getQuitScene());
    }

    public function test_assign_message_with_string_values(): void
    {
        $message = new ServerMessage();
        $data = [
            'UserID' => '',
            'State' => 'empty_state',
            'UpdateDetail' => 'test_detail',
        ];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertEquals('', $message->getUserId());
        $this->assertEquals('empty_state', $message->getState());
        $this->assertEquals('test_detail', $message->getUpdateDetail());
    }

    public function test_assign_message_does_not_affect_unset_properties(): void
    {
        $message = new ServerMessage();
        $message->setToUserName('original_value');
        
        $data = [
            'CreateTime' => 1654355183,
            // ToUserName 没有在data中，应该保持原值
        ];
        
        // 使用反射来测试assignMessage方法
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        $method = $reflection->getMethod('assignMessage');
        
        $repository = $this->getMockBuilder(ServerMessageRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        
        $method->invoke($repository, $message, $data);
        
        $this->assertEquals(1654355183, $message->getCreateTime());
        $this->assertEquals('original_value', $message->getToUserName());
    }

    public function test_repository_inheritance(): void
    {
        $this->assertTrue(
            is_subclass_of(ServerMessageRepository::class, 'Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository')
        );
    }

    public function test_repository_has_assign_message_method(): void
    {
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        
        $this->assertTrue($reflection->hasMethod('assignMessage'));
        
        $method = $reflection->getMethod('assignMessage');
        $this->assertTrue($method->isPublic());
    }

    public function test_repository_has_save_xml_method(): void
    {
        $reflection = new \ReflectionClass(ServerMessageRepository::class);
        
        $this->assertTrue($reflection->hasMethod('saveXML'));
        
        $method = $reflection->getMethod('saveXML');
        $this->assertTrue($method->isPublic());
    }
} 