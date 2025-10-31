<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\XML\XML;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Exception\InvalidXmlException;

/**
 * @extends ServiceEntityRepository<ServerMessage>
 * @method ServerMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerMessage[]    findAll()
 * @method ServerMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @phpstan-ignore-next-line missingType.generics
 */
#[AsRepository(entityClass: ServerMessage::class)]
#[Autoconfigure(public: true)]
class ServerMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerMessage::class);
    }

    public function createFromXML(string $xml): ?ServerMessage
    {
        try {
            $arr = XML::parse($xml);
        } catch (\Throwable $e) {
            throw new InvalidXmlException('xml解析为空', 0, $e);
        }

        if ([] === $arr) {
            throw new InvalidXmlException('xml解析为空');
        }

        $message = ServerMessage::createFromArray($arr);

        if (null === $message->getCreateTime()) {
            return null;
        }
        if (null === $message->getToUserName()) {
            return null;
        }

        return $message;
    }

    /**
     * @param array<string, mixed> $arr
     */
    public function assignMessage(ServerMessage $message, array $arr): void
    {
        // Delegate to the entity's own mapping method
        $tempMessage = ServerMessage::createFromArray($arr);

        // Copy mapped properties to the target message
        $this->copyProperties($tempMessage, $message);
    }

    private function copyProperties(ServerMessage $source, ServerMessage $target): void
    {
        $propertyMappings = [
            'getCreateTime' => 'setCreateTime',
            'getToUserName' => 'setToUserName',
            'getFromUserName' => 'setFromUserName',
            'getMsgType' => 'setMsgType',
            'getEvent' => 'setEvent',
            'getChangeType' => 'setChangeType',
            'getUserId' => 'setUserId',
            'getExternalUserId' => 'setExternalUserId',
            'getWelcomeCode' => 'setWelcomeCode',
            'getChatId' => 'setChatId',
            'getUpdateDetail' => 'setUpdateDetail',
            'getJoinScene' => 'setJoinScene',
            'getMemChangeCnt' => 'setMemChangeCnt',
            'getQuitScene' => 'setQuitScene',
            'getState' => 'setState',
        ];

        foreach ($propertyMappings as $getter => $setter) {
            if (method_exists($source, $getter) && method_exists($target, $setter)) {
                $value = call_user_func([$source, $getter]);
                if (null !== $value) {
                    call_user_func([$target, $setter], $value);
                }
            }
        }
    }

    public function save(ServerMessage $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServerMessage $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
