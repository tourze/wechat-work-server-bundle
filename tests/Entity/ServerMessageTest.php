<?php

namespace WechatWorkServerBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use WechatWorkServerBundle\Entity\ServerMessage;

class ServerMessageTest extends TestCase
{
    public function testCreateInstance(): void
    {
        $serverMessage = new ServerMessage();
        $this->assertInstanceOf(ServerMessage::class, $serverMessage);
    }

    public function testGettersAndSetters(): void
    {
        $serverMessage = new ServerMessage();
        
        $toUserName = 'ww72805907153f7fa3';
        $serverMessage->setToUserName($toUserName);
        $this->assertEquals($toUserName, $serverMessage->getToUserName());
        
        $fromUserName = 'testUser';
        $serverMessage->setFromUserName($fromUserName);
        $this->assertEquals($fromUserName, $serverMessage->getFromUserName());
        
        $createTime = 1654355183;
        $serverMessage->setCreateTime($createTime);
        $this->assertEquals($createTime, $serverMessage->getCreateTime());
        
        $msgType = 'event';
        $serverMessage->setMsgType($msgType);
        $this->assertEquals($msgType, $serverMessage->getMsgType());
        
        $event = 'subscribe';
        $serverMessage->setEvent($event);
        $this->assertEquals($event, $serverMessage->getEvent());
        
        $changeType = 'create';
        $serverMessage->setChangeType($changeType);
        $this->assertEquals($changeType, $serverMessage->getChangeType());
        
        $chatId = 'chatId123';
        $serverMessage->setChatId($chatId);
        $this->assertEquals($chatId, $serverMessage->getChatId());
        
        $externalUserId = 'ext123';
        $serverMessage->setExternalUserId($externalUserId);
        $this->assertEquals($externalUserId, $serverMessage->getExternalUserId());
        
        $joinScene = 1;
        $serverMessage->setJoinScene($joinScene);
        $this->assertEquals($joinScene, $serverMessage->getJoinScene());
        
        $memChangeCnt = 2;
        $serverMessage->setMemChangeCnt($memChangeCnt);
        $this->assertEquals($memChangeCnt, $serverMessage->getMemChangeCnt());
        
        $quitScene = 3;
        $serverMessage->setQuitScene($quitScene);
        $this->assertEquals($quitScene, $serverMessage->getQuitScene());
        
        $state = 'state123';
        $serverMessage->setState($state);
        $this->assertEquals($state, $serverMessage->getState());
        
        $updateDetail = 'detail123';
        $serverMessage->setUpdateDetail($updateDetail);
        $this->assertEquals($updateDetail, $serverMessage->getUpdateDetail());
        
        $userId = 'userId123';
        $serverMessage->setUserId($userId);
        $this->assertEquals($userId, $serverMessage->getUserId());
        
        $welcomeCode = 'welcome123';
        $serverMessage->setWelcomeCode($welcomeCode);
        $this->assertEquals($welcomeCode, $serverMessage->getWelcomeCode());
        
        $rawData = ['key' => 'value'];
        $serverMessage->setRawData($rawData);
        $this->assertEquals($rawData, $serverMessage->getRawData());
        
        $decryptData = ['decrypted' => 'data'];
        $serverMessage->setDecryptData($decryptData);
        $this->assertEquals($decryptData, $serverMessage->getDecryptData());
        
        $response = ['response' => 'content'];
        $serverMessage->setResponse($response);
        $this->assertEquals($response, $serverMessage->getResponse());
    }

    public function testCreateFromArray(): void
    {
        $data = [
            'ToUserName' => 'ww72805907153f7fa3',
            'FromUserName' => 'testUser',
            'CreateTime' => 1654355183,
            'MsgType' => 'event',
            'Event' => 'subscribe',
            'ChangeType' => 'create',
            'ChatId' => 'chatId123',
            'ExternalUserID' => 'ext123',
            'JoinScene' => 1,
            'MemChangeCnt' => 2,
            'QuitScene' => 3,
            'State' => 'state123',
            'UpdateDetail' => 'detail123',
            'UserID' => 'userId123',
            'WelcomeCode' => 'welcome123',
        ];
        
        $serverMessage = ServerMessage::createFromArray($data);
        
        $this->assertInstanceOf(ServerMessage::class, $serverMessage);
        $this->assertEquals($data['ToUserName'], $serverMessage->getToUserName());
        $this->assertEquals($data['FromUserName'], $serverMessage->getFromUserName());
        $this->assertEquals($data['CreateTime'], $serverMessage->getCreateTime());
        $this->assertEquals($data['MsgType'], $serverMessage->getMsgType());
        $this->assertEquals($data['Event'], $serverMessage->getEvent());
        $this->assertEquals($data['ChangeType'], $serverMessage->getChangeType());
        $this->assertEquals($data['ChatId'], $serverMessage->getChatId());
        $this->assertEquals($data['ExternalUserID'], $serverMessage->getExternalUserId());
        $this->assertEquals($data['JoinScene'], $serverMessage->getJoinScene());
        $this->assertEquals($data['MemChangeCnt'], $serverMessage->getMemChangeCnt());
        $this->assertEquals($data['QuitScene'], $serverMessage->getQuitScene());
        $this->assertEquals($data['State'], $serverMessage->getState());
        $this->assertEquals($data['UpdateDetail'], $serverMessage->getUpdateDetail());
        $this->assertEquals($data['UserID'], $serverMessage->getUserId());
        $this->assertEquals($data['WelcomeCode'], $serverMessage->getWelcomeCode());
        $this->assertEquals($data, $serverMessage->getRawData());
    }

    public function testCreateFromArray_withEmptyArray(): void
    {
        $serverMessage = ServerMessage::createFromArray([]);
        
        $this->assertInstanceOf(ServerMessage::class, $serverMessage);
        $this->assertNull($serverMessage->getToUserName());
        $this->assertNull($serverMessage->getFromUserName());
        $this->assertNull($serverMessage->getCreateTime());
        $this->assertEquals([], $serverMessage->getRawData());
    }

    public function testCreateFromArray_withPartialData(): void
    {
        $data = [
            'ToUserName' => 'ww72805907153f7fa3',
            'CreateTime' => 1654355183,
        ];
        
        $serverMessage = ServerMessage::createFromArray($data);
        
        $this->assertInstanceOf(ServerMessage::class, $serverMessage);
        $this->assertEquals($data['ToUserName'], $serverMessage->getToUserName());
        $this->assertEquals($data['CreateTime'], $serverMessage->getCreateTime());
        $this->assertNull($serverMessage->getFromUserName());
        $this->assertNull($serverMessage->getMsgType());
        $this->assertEquals($data, $serverMessage->getRawData());
    }
}
