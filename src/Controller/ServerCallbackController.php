<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\LockInterface;
use Symfony\Component\Routing\Attribute\Route;
use Tourze\WechatHelper\Encryptor;
use Tourze\WechatWorkContracts\AgentInterface;
use Tourze\WechatWorkContracts\CorpInterface;
use Tourze\XML\XML;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;
use WechatWorkServerBundle\Exception\RuntimeException;
use WechatWorkServerBundle\Repository\AgentRepository;
use WechatWorkServerBundle\Repository\CorpRepository;

final class ServerCallbackController extends AbstractController
{
    public function __construct(
        private readonly CorpRepository $corpRepository,
        private readonly AgentRepository $agentRepository,
    ) {
    }

    /**
     * @see https://developer.work.weixin.qq.com/document/path/90240
     * @see https://developer.work.weixin.qq.com/document/path/90239
     * @see https://developer.work.weixin.qq.com/document/path/96238
     */
    #[Route(path: '/wechat/work/server/{corpId}/{agentId}', name: 'wechat_work_server', methods: ['GET', 'POST'])]
    public function __invoke(
        string $corpId,
        string $agentId,
        Request $request,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        LockFactory $lockFactory,
        LoggerInterface $logger,
    ): Response {
        $corp = $this->findCorp($corpId);
        $agent = $this->findAgent($corp, $agentId);

        $corpIdValue = $corp->getCorpId();
        if (null === $corpIdValue) {
            throw new RuntimeException('企业ID不能为空');
        }

        $encryptor = new Encryptor($corpIdValue, $agent->getToken(), $agent->getEncodingAESKey());

        if ($request->query->has('echostr')) {
            return $this->handleEchoString($request, $encryptor);
        }

        $message = $this->decryptAndParseMessage($request, $encryptor, $logger);
        $lock = $this->acquireLock($lockFactory, $message);

        if (null === $lock) {
            return new Response('success');
        }

        $serverMessage = $this->persistServerMessage($message, $corp, $agent, $entityManager);
        $this->dispatchEvent($eventDispatcher, $serverMessage, $logger);
        $this->releaseLock($lock);

        return new Response(null !== $serverMessage->getResponse() ? XML::build($serverMessage->getResponse()) : 'success');
    }

    private function findCorp(string $corpId): CorpInterface
    {
        $corp = $this->corpRepository->findByCorpId($corpId);
        if (null === $corp) {
            throw new NotFoundHttpException('找不到企业');
        }

        return $corp;
    }

    private function findAgent(CorpInterface $corp, string $agentId): AgentInterface
    {
        $agent = $this->agentRepository->findByCorpAndAgentId($corp, $agentId);

        if (null === $agent) {
            $agent = $this->agentRepository->findByCorpAndName($corp, $agentId);
        }

        if (null === $agent) {
            throw new NotFoundHttpException('找不到应用信息');
        }

        return $agent;
    }

    private function handleEchoString(Request $request, Encryptor $encryptor): Response
    {
        $echostr = $request->query->get('echostr');
        if (!is_string($echostr)) {
            throw new BadRequestException('echostr must be a string');
        }

        $msgSignature = $request->query->get('msg_signature');
        $nonce = $request->query->get('nonce');
        $timestamp = $request->query->get('timestamp');

        if (!is_string($msgSignature) || !is_string($nonce) || !is_string($timestamp)) {
            throw new BadRequestException('Required parameters must be strings');
        }

        $message = $encryptor->decrypt($echostr, $msgSignature, $nonce, $timestamp);

        return new Response($message);
    }

    /**
     * @return array<string, mixed>
     */
    private function decryptAndParseMessage(Request $request, Encryptor $encryptor, LoggerInterface $logger): array
    {
        $content = $request->getContent();
        $message = $this->parseMessage($content);

        if ([] === $message) {
            throw new BadRequestException('No message received.');
        }

        $logger->info('收到企业微信服务端消息', $message);

        if (isset($message['Encrypt'])) {
            $msgSignature = $request->query->get('msg_signature');
            $nonce = $request->query->get('nonce');
            $timestamp = $request->query->get('timestamp');

            if (!is_string($msgSignature) || !is_string($nonce) || !is_string($timestamp)) {
                throw new BadRequestException('Required parameters must be strings');
            }

            $encrypt = $message['Encrypt'];
            if (!is_string($encrypt)) {
                throw new BadRequestException('Encrypt parameter must be a string');
            }

            $decryptedMessage = $encryptor->decrypt(
                $encrypt,
                $msgSignature,
                $nonce,
                $timestamp
            );

            $message = $this->parseMessage($decryptedMessage);
            $logger->info('得到解密后的消息内容', [
                'content' => $content,
                'message' => $message,
            ]);
        }

        return $message;
    }

    /**
     * @param array<string, mixed> $message
     */
    private function acquireLock(LockFactory $lockFactory, array $message): ?LockInterface
    {
        $lockKey = null;
        if (isset($message['MsgId'])) {
            $msgId = $message['MsgId'];
            if (is_string($msgId) || is_int($msgId)) {
                $lockKey = 'WechatWorkBundle-Controller-ServerMessageRequestEvent' . $msgId;
            }
        }

        if (null === $lockKey) {
            return new class implements LockInterface {
                public function acquire(bool $blocking = false): bool
                {
                    return true;
                }

                public function refresh(?float $ttl = null): void
                {
                }

                public function isAcquired(): bool
                {
                    return true;
                }

                public function release(): void
                {
                }

                public function isExpired(): bool
                {
                    return false;
                }

                public function getRemainingLifetime(): ?float
                {
                    return null;
                }
            };
        }

        $lock = $lockFactory->createLock($lockKey);

        return $lock->acquire() ? $lock : null;
    }

    /**
     * @param array<string, mixed> $message
     */
    private function persistServerMessage(array $message, CorpInterface $corp, AgentInterface $agent, EntityManagerInterface $entityManager): ServerMessage
    {
        $serverMessage = ServerMessage::createFromArray($message);
        $serverMessage->setCorp($corp);
        $serverMessage->setAgent($agent);
        $entityManager->persist($serverMessage);
        $entityManager->flush();

        return $serverMessage;
    }

    private function dispatchEvent(EventDispatcherInterface $eventDispatcher, ServerMessage $serverMessage, LoggerInterface $logger): void
    {
        $event = new WechatWorkServerMessageRequestEvent();
        $event->setMessage($serverMessage);

        try {
            $eventDispatcher->dispatch($event);
        } catch (\Throwable $exception) {
            $logger->error('企业微信回调时发生未知异常', [
                'event' => $event,
                'exception' => $exception,
            ]);
        }
    }

    private function releaseLock(?LockInterface $lock): void
    {
        $lock?->release();
    }

    /**
     * 解析 PHP 原始输入中的消息数组。
     *
     * @return array<string, mixed>
     */
    private function parseMessage(string $content): array
    {
        try {
            if (0 === stripos($content, '<')) {
                return $this->parseXmlMessage($content);
            }

            return $this->parseJsonMessage($content);
        } catch (\Throwable $e) {
            throw new BadRequestException(sprintf('Invalid message content:(%s) %s', $e->getCode(), $e->getMessage()), $e->getCode());
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function parseXmlMessage(string $content): array
    {
        return XML::parse($content);
    }

    /**
     * @return array<string, mixed>
     */
    private function parseJsonMessage(string $content): array
    {
        $dataSet = json_decode($content, true);
        if (null === $dataSet || (JSON_ERROR_NONE !== json_last_error()) || !is_array($dataSet)) {
            return [];
        }

        return $dataSet;
    }
}
