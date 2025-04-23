<?php

namespace WechatWorkServerBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Keyword;
use Tourze\EasyAdmin\Attribute\Permission\AsPermission;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

#[AsPermission(title: '服务端消息')]
#[ORM\Entity(repositoryClass: ServerMessageRepository::class)]
#[ORM\Table(name: 'wechat_work_server_message', options: ['comment' => '服务端消息'])]
class ServerMessage
{
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'recursive_view', 'api_tree'])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = '0';

    #[Keyword]
    #[ListColumn]
    #[ORM\Column(type: Types::STRING, length: 64, options: ['comment' => '企业微信CorpID'])]
    private ?string $toUserName = null;

    #[ORM\Column(type: Types::STRING, length: 128, nullable: true, options: ['comment' => '成员UserID'])]
    private ?string $fromUserName = null;

    #[ListColumn]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '消息创建时间戳'])]
    private ?int $createTime = null;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => 'Encrypt参数解密后的内容'])]
    private array $decryptData = [];

    #[Keyword]
    #[ORM\Column(type: Types::JSON, nullable: true, options: ['comment' => '原始数据'])]
    private ?array $rawData = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $msgType = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $event = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $changeType = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $chatId = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $externalUserId = null;

    #[ORM\Column(nullable: true)]
    private ?int $joinScene = null;

    #[ORM\Column(nullable: true)]
    private ?int $memChangeCnt = null;

    #[ORM\Column(nullable: true)]
    private ?int $quitScene = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $state = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $updateDetail = null;

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $userId = null;

    #[ORM\Column(length: 140, nullable: true)]
    private ?string $welcomeCode = null;

    #[ORM\ManyToOne]
    private ?Corp $corp = null;

    #[ORM\ManyToOne]
    private ?Agent $agent = null;

    #[ORM\Column(nullable: true)]
    private ?array $response = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getToUserName(): ?string
    {
        return $this->toUserName;
    }

    public function setToUserName(string $toUserName): self
    {
        $this->toUserName = $toUserName;

        return $this;
    }

    public function getCreateTime(): ?int
    {
        return $this->createTime;
    }

    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    public function getRawData(): ?array
    {
        return $this->rawData;
    }

    public function setRawData(?array $rawData): self
    {
        $this->rawData = $rawData;

        return $this;
    }

    public function getFromUserName(): ?string
    {
        return $this->fromUserName;
    }

    public function setFromUserName(?string $fromUserName): self
    {
        $this->fromUserName = $fromUserName;

        return $this;
    }

    public function getDecryptData(): ?array
    {
        return $this->decryptData;
    }

    public function setDecryptData(?array $decryptData): self
    {
        $this->decryptData = $decryptData;

        return $this;
    }

    public function getMsgType(): ?string
    {
        return $this->msgType;
    }

    public function setMsgType(?string $msgType): self
    {
        $this->msgType = $msgType;

        return $this;
    }

    public function getEvent(): ?string
    {
        return $this->event;
    }

    public function setEvent(?string $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getChangeType(): ?string
    {
        return $this->changeType;
    }

    public function setChangeType(?string $changeType): self
    {
        $this->changeType = $changeType;

        return $this;
    }

    public function getChatId(): ?string
    {
        return $this->chatId;
    }

    public function setChatId(?string $chatId): self
    {
        $this->chatId = $chatId;

        return $this;
    }

    public function getExternalUserId(): ?string
    {
        return $this->externalUserId;
    }

    public function setExternalUserId(?string $externalUserId): self
    {
        $this->externalUserId = $externalUserId;

        return $this;
    }

    public function getJoinScene(): ?int
    {
        return $this->joinScene;
    }

    public function setJoinScene(?int $joinScene): self
    {
        $this->joinScene = $joinScene;

        return $this;
    }

    public function getMemChangeCnt(): ?int
    {
        return $this->memChangeCnt;
    }

    public function setMemChangeCnt(?int $memChangeCnt): self
    {
        $this->memChangeCnt = $memChangeCnt;

        return $this;
    }

    public function getQuitScene(): ?int
    {
        return $this->quitScene;
    }

    public function setQuitScene(?int $quitScene): self
    {
        $this->quitScene = $quitScene;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getUpdateDetail(): ?string
    {
        return $this->updateDetail;
    }

    public function setUpdateDetail(?string $updateDetail): self
    {
        $this->updateDetail = $updateDetail;

        return $this;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(?string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getWelcomeCode(): ?string
    {
        return $this->welcomeCode;
    }

    public function setWelcomeCode(?string $welcomeCode): self
    {
        $this->welcomeCode = $welcomeCode;

        return $this;
    }

    public function getCorp(): ?Corp
    {
        return $this->corp;
    }

    public function setCorp(?Corp $corp): static
    {
        $this->corp = $corp;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function getResponse(): ?array
    {
        return $this->response;
    }

    public function setResponse(?array $response): static
    {
        $this->response = $response;

        return $this;
    }

    public static function createFromArray(array $arr): static
    {
        $message = new self();
        $message->setRawData($arr);
        if (isset($arr['CreateTime'])) {
            $message->setCreateTime($arr['CreateTime']);
        }
        if (isset($arr['ToUserName'])) {
            $message->setToUserName($arr['ToUserName']);
        }
        if (isset($arr['FromUserName'])) {
            $message->setFromUserName($arr['FromUserName']);
        }
        if (isset($arr['MsgType'])) {
            $message->setMsgType($arr['MsgType']);
        }
        if (isset($arr['Event'])) {
            $message->setEvent($arr['Event']);
        }
        if (isset($arr['ChangeType'])) {
            $message->setChangeType($arr['ChangeType']);
        }
        if (isset($arr['UserID'])) {
            $message->setUserId($arr['UserID']);
        }
        if (isset($arr['ExternalUserID'])) {
            $message->setExternalUserId($arr['ExternalUserID']);
        }
        if (isset($arr['WelcomeCode'])) {
            $message->setWelcomeCode($arr['WelcomeCode']);
        }
        if (isset($arr['ChatId'])) {
            $message->setChatId($arr['ChatId']);
        }
        if (isset($arr['UpdateDetail'])) {
            $message->setUpdateDetail($arr['UpdateDetail']);
        }
        if (isset($arr['JoinScene'])) {
            $message->setJoinScene($arr['JoinScene']);
        }
        if (isset($arr['MemChangeCnt'])) {
            $message->setMemChangeCnt($arr['MemChangeCnt']);
        }
        if (isset($arr['QuitScene'])) {
            $message->setQuitScene($arr['QuitScene']);
        }
        if (isset($arr['State'])) {
            $message->setState($arr['State']);
        }

        return $message;
    }
}
