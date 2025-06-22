<?php

namespace WechatWorkServerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\XML\XML;
use WechatWorkServerBundle\Entity\ServerMessage;

/**
 * @method ServerMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerMessage[]    findAll()
 * @method ServerMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerMessage::class);
    }

    public function saveXML(string $xml, bool $flush = false): ?ServerMessage
    {
        $arr = XML::parse($xml);
        if (empty($arr)) {
            throw new \InvalidArgumentException('xml解析为空');
        }

        $message = ServerMessage::createFromArray($arr);

        if ($message->getCreateTime() === null) {
            return null;
        }
        if ($message->getToUserName() === null) {
            return null;
        }

        $this->getEntityManager()->persist($message);
        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $message;
    }

    public function assignMessage(ServerMessage $message, array $arr): void
    {
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
    }
}
