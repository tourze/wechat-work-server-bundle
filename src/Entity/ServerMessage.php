<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

#[ORM\Entity(repositoryClass: ServerMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_server_message', options: ['comment' => '服务端消息'])]
final class ServerMessage implements \Stringable
{
    use SnowflakeKeyAware;

    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '企业微信CorpID'])]
    private ?string $toUserName = null;

    #[Assert\Length(max: 128)]
    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '成员UserID'])]
    private ?string $fromUserName = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '消息创建时间戳'])]
    private ?int $createTime = null;

    /**
     * @var array<string, mixed>
     */
    #[Assert\Type(type: 'array')]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => 'Encrypt参数解密后的内容'])]
    private array $decryptData = [];

    /**
     * @var array<string, mixed>|null
     */
    #[Assert\Type(type: 'array')]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '原始数据'])]
    private ?array $rawData = null;

    #[Assert\Length(max: 50)]
    #[ORM\Column(length: 50, nullable: true, options: ['comment' => '消息类型'])]
    private ?string $msgType = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '事件类型'])]
    private ?string $event = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '变更类型'])]
    private ?string $changeType = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '群聊ID'])]
    private ?string $chatId = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '外部联系人ID'])]
    private ?string $externalUserId = null;

    #[Assert\Type(type: 'integer')]
    #[ORM\Column(nullable: true, options: ['comment' => '入群场景'])]
    private ?int $joinScene = null;

    #[Assert\Type(type: 'integer')]
    #[ORM\Column(nullable: true, options: ['comment' => '成员变更数量'])]
    private ?int $memChangeCnt = null;

    #[Assert\Type(type: 'integer')]
    #[ORM\Column(nullable: true, options: ['comment' => '退群场景'])]
    private ?int $quitScene = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '自定义状态'])]
    private ?string $state = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '更新详情'])]
    private ?string $updateDetail = null;

    #[Assert\Length(max: 120)]
    #[ORM\Column(length: 120, nullable: true, options: ['comment' => '用户ID'])]
    private ?string $userId = null;

    #[Assert\Length(max: 140)]
    #[ORM\Column(length: 140, nullable: true, options: ['comment' => '欢迎语code'])]
    private ?string $welcomeCode = null;

    #[ORM\ManyToOne(targetEntity: CorpInterface::class)]
    #[ORM\JoinColumn(name: 'corp_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?CorpInterface $corp = null;

    #[ORM\ManyToOne(targetEntity: AgentInterface::class)]
    #[ORM\JoinColumn(name: 'agent_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?AgentInterface $agent = null;

    /**
     * @var array<string, mixed>|null
     */
    #[Assert\Type(type: 'array')]
    #[ORM\Column(nullable: true, options: ['comment' => '响应数据'])]
    private ?array $response = null;

    public function getToUserName(): ?string
    {
        return $this->toUserName;
    }

    public function setToUserName(string $toUserName): void
    {
        $this->toUserName = $toUserName;
    }

    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): void
    {
        $this->createTime = $createTime;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRawData(): ?array
    {
        return $this->rawData;
    }

    /**
     * @param array<string, mixed>|null $rawData
     */
    public function setRawData(?array $rawData): void
    {
        $this->rawData = $rawData;
    }

    public function getFromUserName(): ?string
    {
        return $this->fromUserName;
    }

    public function setFromUserName(?string $fromUserName): void
    {
        $this->fromUserName = $fromUserName;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDecryptData(): array
    {
        return $this->decryptData;
    }

    /**
     * @param array<string, mixed>|null $decryptData
     */
    public function setDecryptData(?array $decryptData): void
    {
        $this->decryptData = $decryptData ?? [];
    }

    public function getMsgType(): ?string
    {
        return $this->msgType;
    }

    public function setMsgType(?string $msgType): void
    {
        $this->msgType = $msgType;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): void
    {
        $this->event = $event;
    }

    public function getChangeType(): ?string
    {
        return $this->changeType;
    }

    public function setChangeType(?string $changeType): void
    {
        $this->changeType = $changeType;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function setChatId(?string $chatId): void
    {
        $this->chatId = $chatId;
    }

    public function getExternalUserId(): ?string
    {
        return $this->externalUserId;
    }

    public function setExternalUserId(?string $externalUserId): void
    {
        $this->externalUserId = $externalUserId;
    }

    public function getJoinScene(): ?int
    {
        return $this->joinScene;
    }

    public function setJoinScene(?int $joinScene): void
    {
        $this->joinScene = $joinScene;
    }

    public function getMemChangeCnt(): ?int
    {
        return $this->memChangeCnt;
    }

    public function setMemChangeCnt(?int $memChangeCnt): void
    {
        $this->memChangeCnt = $memChangeCnt;
    }

    public function getQuitScene(): ?int
    {
        return $this->quitScene;
    }

    public function setQuitScene(?int $quitScene): void
    {
        $this->quitScene = $quitScene;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function getUpdateDetail(): ?string
    {
        return $this->updateDetail;
    }

    public function setUpdateDetail(?string $updateDetail): void
    {
        $this->updateDetail = $updateDetail;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    public function getWelcomeCode(): ?string
    {
        return $this->welcomeCode;
    }

    public function setWelcomeCode(?string $welcomeCode): void
    {
        $this->welcomeCode = $welcomeCode;
    }

    public function getCorp(): ?CorpInterface
    {
        return $this->corp;
    }

    public function setCorp(?CorpInterface $corp): void
    {
        $this->corp = $corp;
    }

    public function getAgent(): ?AgentInterface
    {
        return $this->agent;
    }

    public function setAgent(?AgentInterface $agent): void
    {
        $this->agent = $agent;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getResponse(): ?array
    {
        return $this->response;
    }

    /**
     * @param array<string, mixed>|null $response
     */
    public function setResponse(?array $response): void
    {
        $this->response = $response;
    }

    /**
     * @param array<string, mixed> $arr
     */
    public static function createFromArray(array $arr): static
    {
        $message = self::createNewInstance();
        $message->setRawData($arr);
        $message->mapArrayToProperties($arr);

        return $message;
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapArrayToProperties(array $arr): void
    {
        $this->mapRequiredFields($arr);
        $this->mapOptionalStringFields($arr);
        $this->mapOptionalIntegerFields($arr);
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapRequiredFields(array $arr): void
    {
        if (isset($arr['CreateTime'])) {
            if (is_int($arr['CreateTime'])) {
                $this->setCreateTime($arr['CreateTime']);
            } elseif (is_string($arr['CreateTime']) && is_numeric($arr['CreateTime'])) {
                $this->setCreateTime((int) $arr['CreateTime']);
            }
        }
        if (isset($arr['ToUserName']) && is_string($arr['ToUserName'])) {
            $this->setToUserName($arr['ToUserName']);
        }
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapOptionalStringFields(array $arr): void
    {
        $this->mapBasicStringFields($arr);
        $this->mapEventStringFields($arr);
        $this->mapIdentifierStringFields($arr);
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapBasicStringFields(array $arr): void
    {
        if (isset($arr['FromUserName']) && is_string($arr['FromUserName'])) {
            $this->setFromUserName($arr['FromUserName']);
        }
        if (isset($arr['MsgType']) && is_string($arr['MsgType'])) {
            $this->setMsgType($arr['MsgType']);
        }
        if (isset($arr['State']) && is_string($arr['State'])) {
            $this->setState($arr['State']);
        }
        if (isset($arr['UpdateDetail']) && is_string($arr['UpdateDetail'])) {
            $this->setUpdateDetail($arr['UpdateDetail']);
        }
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapEventStringFields(array $arr): void
    {
        if (isset($arr['Event']) && is_string($arr['Event'])) {
            $this->setEvent($arr['Event']);
        }
        if (isset($arr['ChangeType']) && is_string($arr['ChangeType'])) {
            $this->setChangeType($arr['ChangeType']);
        }
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapIdentifierStringFields(array $arr): void
    {
        if (isset($arr['UserID']) && is_string($arr['UserID'])) {
            $this->setUserId($arr['UserID']);
        }
        if (isset($arr['ExternalUserID']) && is_string($arr['ExternalUserID'])) {
            $this->setExternalUserId($arr['ExternalUserID']);
        }
        if (isset($arr['WelcomeCode']) && is_string($arr['WelcomeCode'])) {
            $this->setWelcomeCode($arr['WelcomeCode']);
        }
        if (isset($arr['ChatId']) && is_string($arr['ChatId'])) {
            $this->setChatId($arr['ChatId']);
        }
    }

    /**
     * @param array<string, mixed> $arr
     */
    private function mapOptionalIntegerFields(array $arr): void
    {
        if (isset($arr['JoinScene']) && is_int($arr['JoinScene'])) {
            $this->setJoinScene($arr['JoinScene']);
        }
        if (isset($arr['MemChangeCnt']) && is_int($arr['MemChangeCnt'])) {
            $this->setMemChangeCnt($arr['MemChangeCnt']);
        }
        if (isset($arr['QuitScene']) && is_int($arr['QuitScene'])) {
            $this->setQuitScene($arr['QuitScene']);
        }
    }

    private static function createNewInstance(): static
    {
        return new static();
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
