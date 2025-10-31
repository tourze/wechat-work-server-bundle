<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkBundle\Entity\Agent;

/**
 * @extends ServiceEntityRepository<Agent>
 * @method Agent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agent[]    findAll()
 * @method Agent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @phpstan-ignore-next-line missingType.generics
 */
#[AsRepository(entityClass: Agent::class)]
#[Autoconfigure(public: true)]
final class AgentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agent::class);
    }

    public function findByCorpAndAgentId(CorpInterface $corp, string $agentId): ?AgentInterface
    {
        return $this->findOneBy([
            'corp' => $corp,
            'agentId' => $agentId,
        ]);
    }

    public function findByCorpAndName(CorpInterface $corp, string $name): ?AgentInterface
    {
        return $this->findOneBy([
            'corp' => $corp,
            'name' => $name,
        ]);
    }

    public function save(Agent $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Agent $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
