<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\PHPUnitSymfonyKernelTest\Attribute\AsRepository;
use Tourze\WechatWorkContracts\CorpInterface;
use WechatWorkBundle\Entity\Corp;

/**
 * @extends ServiceEntityRepository<Corp>
 * @method Corp|null find($id, $lockMode = null, $lockVersion = null)
 * @method Corp|null findOneBy(array $criteria, array $orderBy = null)
 * @method Corp[]    findAll()
 * @method Corp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @phpstan-ignore-next-line missingType.generics
 */
#[AsRepository(entityClass: Corp::class)]
#[Autoconfigure(public: true)]
final class CorpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Corp::class);
    }

    public function findByCorpId(string $corpId): ?CorpInterface
    {
        return $this->findOneBy(['corpId' => $corpId]);
    }

    public function save(Corp $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Corp $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
