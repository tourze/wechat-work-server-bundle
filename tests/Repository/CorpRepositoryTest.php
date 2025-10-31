<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatWorkBundle\Entity\Corp;
use WechatWorkServerBundle\Repository\CorpRepository;

/**
 * @internal
 */
#[CoversClass(CorpRepository::class)]
#[RunTestsInSeparateProcesses]
final class CorpRepositoryTest extends AbstractRepositoryTestCase
{
    private CorpRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CorpRepository::class);
    }

    protected function createNewEntity(): object
    {
        // 现在使用具体实体类Corp
        $corp = new Corp();
        $corp->setCorpId('test-corp-' . uniqid());
        $corp->setName('Test Corp ' . uniqid());
        $corp->setCorpSecret('test-secret-' . uniqid());

        return $corp;
    }

    protected function getRepository(): CorpRepository
    {
        return $this->repository;
    }

    public function testFindByCorpIdReturnsNullWhenNotFound(): void
    {
        $result = $this->repository->findByCorpId('non-existent-corp-id');

        self::assertNull($result);
    }
}
