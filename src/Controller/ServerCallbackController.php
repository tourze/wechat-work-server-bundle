<?php

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
use Symfony\Component\Routing\Attribute\Route;
use Tourze\WechatHelper\Encryptor;
use Tourze\XML\XML;
use WechatWorkBundle\Repository\AgentRepository;
use WechatWorkBundle\Repository\CorpRepository;
use WechatWorkServerBundle\Entity\ServerMessage;
use WechatWorkServerBundle\Event\WechatWorkServerMessageRequestEvent;

class ServerCallbackController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

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
        CorpRepository $corpRepository,
        AgentRepository $agentRepository,
        EventDispatcherInterface $eventDispatcher,
        LockFactory $lockFactory,
        LoggerInterface $logger,
    ): Response {
        $corp = $corpRepository->findOneBy(['corpId' => $corpId]);
        if ($corp === null) {
            throw new NotFoundHttpException('找不到企业');
        }

        $agent = $agentRepository->findOneBy([
            'corp' => $corp,
            'agentId' => $agentId,
        ]);
        if ($agent === null) {
            // 在一些场景中，我们放出去的地址，可能是带名称的
            $agent = $agentRepository->findOneBy([
                'corp' => $corp,
                'name' => $agentId,
            ]);
        }
        if ($agent === null) {
            throw new NotFoundHttpException('找不到应用信息');
        }

        $encryptor = new Encryptor($corp->getCorpId(), $agent->getToken(), $agent->getEncodingAESKey());
        if ($request->query->has('echostr')) {
            $echostr = $request->query->get('echostr');
            $message = $encryptor->decrypt(
                $echostr,
                $request->query->get('msg_signature'),
                $request->query->get('nonce'),
                $request->query->get('timestamp')
            );

            return new Response($message);
        }

        $content = $request->getContent();

        $message = $this->parseMessage($content);
        if (!is_array($message) || empty($message)) {
            throw new BadRequestException('No message received.');
        }

        $logger->info('收到企业微信服务端消息', $message);

        if (isset($message['Encrypt'])) {
            $message = $encryptor->decrypt(
                $message['Encrypt'],
                $request->query->get('msg_signature'),
                $request->query->get('nonce'),
                $request->query->get('timestamp')
            );

            $message = $this->parseMessage($message);
            $logger->info('得到解密后的消息内容', [
                'content' => $content,
                'message' => $message,
            ]);
        }

        // 客服消息：
        //  "ToUserName" => "ww72805907153f7fa3"
        //  "CreateTime" => "1654355183"
        //  "MsgType" => "event"
        //  "Event" => "kf_msg_or_event"
        //  "Token" => "ENCHJXDLX9RWoX3SBRZhaNFdjk6C5wYrARXcNhH6DbpemnK"

        // 重复消息的处理
        $lock = null;
        $lockKey = isset($message['MsgId']) ? 'WechatWorkBundle-Controller-ServerMessageRequestEvent' . $message['MsgId'] : false;
        if ($lockKey) {
            $lock = $lockFactory->createLock($lockKey);
            if (!$lock->acquire()) {
                return new Response('success');
            }
        }

        $serverMessage = ServerMessage::createFromArray($message);
        $serverMessage->setCorp($corp);
        $serverMessage->setAgent($agent);
        $this->entityManager->persist($serverMessage);
        $this->entityManager->flush();

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

        if ($lockKey && $lock !== null) {
            $lock->release();
        }

        return new Response($serverMessage->getResponse() !== null ? XML::build($serverMessage->getResponse()) : 'success');
    }

    /**
     * Parse message array from raw php input.
     *
     * @param string $content
     *
     * @return array
     */
    private function parseMessage($content)
    {
        try {
            if (0 === stripos($content, '<')) {
                $content = XML::parse($content);
            } else {
                // Handle JSON format.
                $dataSet = json_decode($content, true);
                if ($dataSet !== null && (JSON_ERROR_NONE === json_last_error())) {
                    $content = $dataSet;
                }
            }

            return (array) $content;
        } catch (\Throwable $e) {
            throw new BadRequestException(sprintf('Invalid message content:(%s) %s', $e->getCode(), $e->getMessage()), $e->getCode());
        }
    }
}