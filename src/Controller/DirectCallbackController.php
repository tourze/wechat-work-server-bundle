<?php

namespace WechatWorkServerBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Attribute\Route;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

final class DirectCallbackController extends AbstractController
{
    #[Route(path: '/wechat/work/direct-server/{corpId}', name: 'wechat_work_server_direct_callback', methods: ['GET', 'POST'])]
    public function __invoke(
        string $corpId,
        Request $request,
        KernelInterface $kernel,
        ServerMessageRepository $messageRepository,
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
    ): Response {
        $corpId = str_replace('..', '', $corpId);
        $logFile = $kernel->getProjectDir() . "/wechat-work-{$corpId}.log";
        file_put_contents($logFile, $request->getContent() . "\n", FILE_APPEND);

        try {
            $message = $messageRepository->createFromXML($request->getContent());
            if (null !== $message) {
                $entityManager->persist($message);
                $entityManager->flush();
            }
        } catch (\Throwable $exception) {
            $logger->error('保存到数据库时发生错误', [
                'exception' => $exception,
            ]);
        }

        return new Response('success');
    }
}
