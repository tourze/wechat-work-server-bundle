<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkServerBundle\Entity\ServerMessage;

/**
 * @internal
 */
#[CoversClass(ServerMessage::class)]
final class ServerMessageTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new ServerMessage();
    }

    /**
     * @return iterable<array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        return [
            ['decryptData', ['key' => 'value']],
        ];
    }

    public function testEntityCreationSuccess(): void
    {
        $serverMessage = new ServerMessage();
        $this->assertInstanceOf(ServerMessage::class, $serverMessage);
        $this->assertNull($serverMessage->getId());
    }

    public function testSetAndGetToUserName(): void
    {
        $toUserName = 'test_corp_id';
        $serverMessage = new ServerMessage();

        $serverMessage->setToUserName($toUserName);
        $this->assertEquals($toUserName, $serverMessage->getToUserName());
    }

    public function testSetAndGetFromUserName(): void
    {
        $fromUserName = 'test_user_id';
        $serverMessage = new ServerMessage();

        $serverMessage->setFromUserName($fromUserName);
        $this->assertEquals($fromUserName, $serverMessage->getFromUserName());
    }

    public function testSetAndGetCreateTime(): void
    {
        $createTime = 1654355183;
        $serverMessage = new ServerMessage();

        $serverMessage->setCreateTime($createTime);
        $this->assertEquals($createTime, $serverMessage->getCreateTime());
    }

    public function testSetAndGetRawData(): void
    {
        $rawData = ['key' => 'value', 'number' => 123];
        $serverMessage = new ServerMessage();

        $serverMessage->setRawData($rawData);
        $this->assertEquals($rawData, $serverMessage->getRawData());
    }

    public function testSetAndGetDecryptData(): void
    {
        $decryptData = ['encrypted' => 'data', 'test' => true];
        $serverMessage = new ServerMessage();

        $serverMessage->setDecryptData($decryptData);
        $this->assertEquals($decryptData, $serverMessage->getDecryptData());
    }

    public function testSetAndGetMsgType(): void
    {
        $msgType = 'event';
        $serverMessage = new ServerMessage();

        $serverMessage->setMsgType($msgType);
        $this->assertEquals($msgType, $serverMessage->getMsgType());
    }

    public function testSetAndGetEvent(): void
    {
        $event = 'kf_msg_or_event';
        $serverMessage = new ServerMessage();

        $serverMessage->setEvent($event);
        $this->assertEquals($event, $serverMessage->getEvent());
    }

    public function testSetAndGetChangeType(): void
    {
        $changeType = 'add_external_contact';
        $serverMessage = new ServerMessage();

        $serverMessage->setChangeType($changeType);
        $this->assertEquals($changeType, $serverMessage->getChangeType());
    }

    public function testSetAndGetChatId(): void
    {
        $chatId = 'chat123456';
        $serverMessage = new ServerMessage();

        $serverMessage->setChatId($chatId);
        $this->assertEquals($chatId, $serverMessage->getChatId());
    }

    public function testSetAndGetExternalUserId(): void
    {
        $externalUserId = 'external_user_123';
        $serverMessage = new ServerMessage();

        $serverMessage->setExternalUserId($externalUserId);
        $this->assertEquals($externalUserId, $serverMessage->getExternalUserId());
    }

    public function testSetAndGetJoinScene(): void
    {
        $joinScene = 1;
        $serverMessage = new ServerMessage();

        $serverMessage->setJoinScene($joinScene);
        $this->assertEquals($joinScene, $serverMessage->getJoinScene());
    }

    public function testSetAndGetMemChangeCnt(): void
    {
        $memChangeCnt = 5;
        $serverMessage = new ServerMessage();

        $serverMessage->setMemChangeCnt($memChangeCnt);
        $this->assertEquals($memChangeCnt, $serverMessage->getMemChangeCnt());
    }

    public function testSetAndGetQuitScene(): void
    {
        $quitScene = 2;
        $serverMessage = new ServerMessage();

        $serverMessage->setQuitScene($quitScene);
        $this->assertEquals($quitScene, $serverMessage->getQuitScene());
    }

    public function testSetAndGetState(): void
    {
        $state = 'test_state';
        $serverMessage = new ServerMessage();

        $serverMessage->setState($state);
        $this->assertEquals($state, $serverMessage->getState());
    }

    public function testSetAndGetUpdateDetail(): void
    {
        $updateDetail = 'test_update_detail';
        $serverMessage = new ServerMessage();

        $serverMessage->setUpdateDetail($updateDetail);
        $this->assertEquals($updateDetail, $serverMessage->getUpdateDetail());
    }

    public function testSetAndGetUserId(): void
    {
        $userId = 'test_user_id';
        $serverMessage = new ServerMessage();

        $serverMessage->setUserId($userId);
        $this->assertEquals($userId, $serverMessage->getUserId());
    }

    public function testSetAndGetWelcomeCode(): void
    {
        $welcomeCode = 'welcome_code_123';
        $serverMessage = new ServerMessage();

        $serverMessage->setWelcomeCode($welcomeCode);
        $this->assertEquals($welcomeCode, $serverMessage->getWelcomeCode());
    }

    public function testSetAndGetCorp(): void
    {
        $corp = new class implements CorpInterface {
            public function getCorpId(): string
            {
                return 'test-corp-id';
            }

            public function getCorpSecret(): string
            {
                return 'test-secret';
            }
        };
        $serverMessage = new ServerMessage();

        $serverMessage->setCorp($corp);
        $this->assertSame($corp, $serverMessage->getCorp());
    }

    public function testSetAndGetResponse(): void
    {
        $response = ['response' => 'success', 'data' => 'test'];
        $serverMessage = new ServerMessage();

        $serverMessage->setResponse($response);
        $this->assertEquals($response, $serverMessage->getResponse());
    }

    public function testCreateFromArrayWithCompleteData(): void
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

    public function testCreateFromArrayWithPartialData(): void
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

    public function testCreateFromArrayWithEmptyData(): void
    {
        $data = [];

        $message = ServerMessage::createFromArray($data);

        $this->assertEquals($data, $message->getRawData());
        $this->assertNull($message->getCreateTime());
        $this->assertNull($message->getToUserName());
        $this->assertNull($message->getFromUserName());
    }

    public function testSetNullValues(): void
    {
        $serverMessage = new ServerMessage();
        $serverMessage->setFromUserName(null);
        $serverMessage->setRawData(null);
        $serverMessage->setMsgType(null);
        $serverMessage->setEvent(null);
        $serverMessage->setChangeType(null);
        $serverMessage->setChatId(null);
        $serverMessage->setExternalUserId(null);
        $serverMessage->setJoinScene(null);
        $serverMessage->setMemChangeCnt(null);
        $serverMessage->setQuitScene(null);
        $serverMessage->setState(null);
        $serverMessage->setUpdateDetail(null);
        $serverMessage->setUserId(null);
        $serverMessage->setWelcomeCode(null);
        $serverMessage->setCorp(null);
        $serverMessage->setAgent(null);
        $serverMessage->setResponse(null);

        $this->assertNull($serverMessage->getFromUserName());
        $this->assertNull($serverMessage->getRawData());
        $this->assertEquals([], $serverMessage->getDecryptData());
        $this->assertNull($serverMessage->getMsgType());
        $this->assertNull($serverMessage->getEvent());
        $this->assertNull($serverMessage->getChangeType());
        $this->assertNull($serverMessage->getChatId());
        $this->assertNull($serverMessage->getExternalUserId());
        $this->assertNull($serverMessage->getJoinScene());
        $this->assertNull($serverMessage->getMemChangeCnt());
        $this->assertNull($serverMessage->getQuitScene());
        $this->assertNull($serverMessage->getState());
        $this->assertNull($serverMessage->getUpdateDetail());
        $this->assertNull($serverMessage->getUserId());
        $this->assertNull($serverMessage->getWelcomeCode());
        $this->assertNull($serverMessage->getCorp());
        $this->assertNull($serverMessage->getAgent());
        $this->assertNull($serverMessage->getResponse());
    }

    public function testSetDecryptDataWithEmptyArray(): void
    {
        $emptyArray = [];
        $serverMessage = new ServerMessage();

        $serverMessage->setDecryptData($emptyArray);
        $this->assertEquals($emptyArray, $serverMessage->getDecryptData());
    }

    public function testDecryptDataDefaultValue(): void
    {
        $newMessage = new ServerMessage();

        $this->assertEquals([], $newMessage->getDecryptData());
    }
}
