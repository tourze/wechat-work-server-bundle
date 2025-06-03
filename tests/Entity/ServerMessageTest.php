<?php

namespace WechatWorkServerBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkServerBundle\Entity\ServerMessage;

class ServerMessageTest extends TestCase
{
    private ServerMessage $serverMessage;

    protected function setUp(): void
    {
        $this->serverMessage = new ServerMessage();
    }

    public function test_entity_creation_success(): void
    {
        $this->assertInstanceOf(ServerMessage::class, $this->serverMessage);
        $this->assertNull($this->serverMessage->getId());
    }

    public function test_set_and_get_to_user_name(): void
    {
        $toUserName = 'test_corp_id';
        
        $result = $this->serverMessage->setToUserName($toUserName);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($toUserName, $this->serverMessage->getToUserName());
    }

    public function test_set_and_get_from_user_name(): void
    {
        $fromUserName = 'test_user_id';
        
        $result = $this->serverMessage->setFromUserName($fromUserName);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($fromUserName, $this->serverMessage->getFromUserName());
    }

    public function test_set_and_get_create_time(): void
    {
        $createTime = 1654355183;
        
        $result = $this->serverMessage->setCreateTime($createTime);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($createTime, $this->serverMessage->getCreateTime());
    }

    public function test_set_and_get_raw_data(): void
    {
        $rawData = ['key' => 'value', 'number' => 123];
        
        $result = $this->serverMessage->setRawData($rawData);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($rawData, $this->serverMessage->getRawData());
    }

    public function test_set_and_get_decrypt_data(): void
    {
        $decryptData = ['encrypted' => 'data', 'test' => true];
        
        $result = $this->serverMessage->setDecryptData($decryptData);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($decryptData, $this->serverMessage->getDecryptData());
    }

    public function test_set_and_get_msg_type(): void
    {
        $msgType = 'event';
        
        $result = $this->serverMessage->setMsgType($msgType);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($msgType, $this->serverMessage->getMsgType());
    }

    public function test_set_and_get_event(): void
    {
        $event = 'kf_msg_or_event';
        
        $result = $this->serverMessage->setEvent($event);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($event, $this->serverMessage->getEvent());
    }

    public function test_set_and_get_change_type(): void
    {
        $changeType = 'add_external_contact';
        
        $result = $this->serverMessage->setChangeType($changeType);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($changeType, $this->serverMessage->getChangeType());
    }

    public function test_set_and_get_chat_id(): void
    {
        $chatId = 'chat123456';
        
        $result = $this->serverMessage->setChatId($chatId);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($chatId, $this->serverMessage->getChatId());
    }

    public function test_set_and_get_external_user_id(): void
    {
        $externalUserId = 'external_user_123';
        
        $result = $this->serverMessage->setExternalUserId($externalUserId);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($externalUserId, $this->serverMessage->getExternalUserId());
    }

    public function test_set_and_get_join_scene(): void
    {
        $joinScene = 1;
        
        $result = $this->serverMessage->setJoinScene($joinScene);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($joinScene, $this->serverMessage->getJoinScene());
    }

    public function test_set_and_get_mem_change_cnt(): void
    {
        $memChangeCnt = 5;
        
        $result = $this->serverMessage->setMemChangeCnt($memChangeCnt);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($memChangeCnt, $this->serverMessage->getMemChangeCnt());
    }

    public function test_set_and_get_quit_scene(): void
    {
        $quitScene = 2;
        
        $result = $this->serverMessage->setQuitScene($quitScene);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($quitScene, $this->serverMessage->getQuitScene());
    }

    public function test_set_and_get_state(): void
    {
        $state = 'test_state';
        
        $result = $this->serverMessage->setState($state);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($state, $this->serverMessage->getState());
    }

    public function test_set_and_get_update_detail(): void
    {
        $updateDetail = 'test_update_detail';
        
        $result = $this->serverMessage->setUpdateDetail($updateDetail);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($updateDetail, $this->serverMessage->getUpdateDetail());
    }

    public function test_set_and_get_user_id(): void
    {
        $userId = 'test_user_id';
        
        $result = $this->serverMessage->setUserId($userId);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($userId, $this->serverMessage->getUserId());
    }

    public function test_set_and_get_welcome_code(): void
    {
        $welcomeCode = 'welcome_code_123';
        
        $result = $this->serverMessage->setWelcomeCode($welcomeCode);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($welcomeCode, $this->serverMessage->getWelcomeCode());
    }

    public function test_set_and_get_corp(): void
    {
        $corp = $this->createMock(CorpInterface::class);
        
        $result = $this->serverMessage->setCorp($corp);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertSame($corp, $this->serverMessage->getCorp());
    }

    public function test_set_and_get_agent(): void
    {
        $agent = $this->createMock(AgentInterface::class);
        
        $result = $this->serverMessage->setAgent($agent);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertSame($agent, $this->serverMessage->getAgent());
    }

    public function test_set_and_get_response(): void
    {
        $response = ['response' => 'success', 'data' => 'test'];
        
        $result = $this->serverMessage->setResponse($response);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($response, $this->serverMessage->getResponse());
    }

    public function test_create_from_array_with_complete_data(): void
    {
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
        
        $message = ServerMessage::createFromArray($data);
        
        $this->assertInstanceOf(ServerMessage::class, $message);
        $this->assertEquals($data, $message->getRawData());
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

    public function test_create_from_array_with_partial_data(): void
    {
        $data = [
            'CreateTime' => 1654355183,
            'ToUserName' => 'ww72805907153f7fa3',
            'MsgType' => 'event',
        ];
        
        $message = ServerMessage::createFromArray($data);
        
        $this->assertEquals($data, $message->getRawData());
        $this->assertEquals(1654355183, $message->getCreateTime());
        $this->assertEquals('ww72805907153f7fa3', $message->getToUserName());
        $this->assertEquals('event', $message->getMsgType());
        $this->assertNull($message->getFromUserName());
        $this->assertNull($message->getEvent());
    }

    public function test_create_from_array_with_empty_data(): void
    {
        $data = [];
        
        $message = ServerMessage::createFromArray($data);
        
        $this->assertEquals($data, $message->getRawData());
        $this->assertNull($message->getCreateTime());
        $this->assertNull($message->getToUserName());
        $this->assertNull($message->getFromUserName());
    }

    public function test_set_null_values(): void
    {
        $this->serverMessage->setFromUserName(null);
        $this->serverMessage->setRawData(null);
        $this->serverMessage->setMsgType(null);
        $this->serverMessage->setEvent(null);
        $this->serverMessage->setChangeType(null);
        $this->serverMessage->setChatId(null);
        $this->serverMessage->setExternalUserId(null);
        $this->serverMessage->setJoinScene(null);
        $this->serverMessage->setMemChangeCnt(null);
        $this->serverMessage->setQuitScene(null);
        $this->serverMessage->setState(null);
        $this->serverMessage->setUpdateDetail(null);
        $this->serverMessage->setUserId(null);
        $this->serverMessage->setWelcomeCode(null);
        $this->serverMessage->setCorp(null);
        $this->serverMessage->setAgent(null);
        $this->serverMessage->setResponse(null);
        
        $this->assertNull($this->serverMessage->getFromUserName());
        $this->assertNull($this->serverMessage->getRawData());
        $this->assertEquals([], $this->serverMessage->getDecryptData());
        $this->assertNull($this->serverMessage->getMsgType());
        $this->assertNull($this->serverMessage->getEvent());
        $this->assertNull($this->serverMessage->getChangeType());
        $this->assertNull($this->serverMessage->getChatId());
        $this->assertNull($this->serverMessage->getExternalUserId());
        $this->assertNull($this->serverMessage->getJoinScene());
        $this->assertNull($this->serverMessage->getMemChangeCnt());
        $this->assertNull($this->serverMessage->getQuitScene());
        $this->assertNull($this->serverMessage->getState());
        $this->assertNull($this->serverMessage->getUpdateDetail());
        $this->assertNull($this->serverMessage->getUserId());
        $this->assertNull($this->serverMessage->getWelcomeCode());
        $this->assertNull($this->serverMessage->getCorp());
        $this->assertNull($this->serverMessage->getAgent());
        $this->assertNull($this->serverMessage->getResponse());
    }

    public function test_set_decrypt_data_with_empty_array(): void
    {
        $emptyArray = [];
        
        $result = $this->serverMessage->setDecryptData($emptyArray);
        
        $this->assertSame($this->serverMessage, $result);
        $this->assertEquals($emptyArray, $this->serverMessage->getDecryptData());
    }

    public function test_decrypt_data_default_value(): void
    {
        $newMessage = new ServerMessage();
        
        $this->assertEquals([], $newMessage->getDecryptData());
    }
} 