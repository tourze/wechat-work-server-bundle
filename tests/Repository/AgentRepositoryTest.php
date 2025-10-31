<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Repository;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Agent;
use WechatWorkBundle\Entity\Corp;
use WechatWorkServerBundle\Repository\AgentRepository;

/**
 * @internal
 */
#[CoversClass(AgentRepository::class)]
#[RunTestsInSeparateProcesses]
final class AgentRepositoryTest extends AbstractRepositoryTestCase
{
    private AgentRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(AgentRepository::class);
    }

    protected function createNewEntity(): object
    {
        // 创建一个Corp实体并先保存
        $corp = new Corp();
        $corp->setCorpId('test-corp-' . uniqid());
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpSecret('test-secret-' . uniqid());

        // 先持久化Corp实体
        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($corp);
        $entityManager->flush();

        // 创建Agent实体并设置必填字段
        $agent = new Agent();
        $agent->setAgentId('test-agent-' . uniqid());
        $agent->setName('Test Agent ' . uniqid());
        $agent->setSecret('test-agent-secret-' . uniqid());
        $agent->setCorp($corp);

        return $agent;
    }

    protected function getRepository(): AgentRepository
    {
        return $this->repository;
    }

    public function testFindByCorpAndAgentIdReturnsNullWhenNotFound(): void
    {
        // 创建一个真实的Corp实体用于查询
        $corp = new Corp();
        $corp->setCorpId('test-corp-not-found-' . uniqid());
        $corp->setName('Test Corp Not Found ' . uniqid());
        $corp->setCorpSecret('test-secret-not-found-' . uniqid());

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($corp);
        $entityManager->flush();

        $result = $this->repository->findByCorpAndAgentId($corp, 'non-existent-agent');

        self::assertNull($result);
    }

    public function testFindByCorpAndNameReturnsNullWhenNotFound(): void
    {
        // 创建一个真实的Corp实体用于查询
        $corp = new Corp();
        $corp->setCorpId('test-corp-not-found-2-' . uniqid());
        $corp->setName('Test Corp Not Found 2 ' . uniqid());
        $corp->setCorpSecret('test-secret-not-found-2-' . uniqid());

        $entityManager = self::getService(EntityManagerInterface::class);
        $entityManager->persist($corp);
        $entityManager->flush();

        $result = $this->repository->findByCorpAndName($corp, 'non-existent-name');

        self::assertNull($result);
    }
}
