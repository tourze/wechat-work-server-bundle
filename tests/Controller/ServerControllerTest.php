<?php

namespace WechatWorkServerBundle\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Lock\LockFactory;
use WechatWorkBundle\Entity\Corp;
use WechatWorkBundle\Repository\AgentRepository;
use WechatWorkBundle\Repository\CorpRepository;
use WechatWorkServerBundle\Controller\ServerController;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

class ServerControllerTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private ServerController $controller;
    private CorpRepository $corpRepository;
    private AgentRepository $agentRepository;
    private EventDispatcherInterface $eventDispatcher;
    private LockFactory $lockFactory;
    private LoggerInterface $logger;
    private KernelInterface $kernel;
    private ServerMessageRepository $serverMessageRepository;
    
    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new ServerController($this->entityManager);
        
        $this->corpRepository = $this->createMock(CorpRepository::class);
        $this->agentRepository = $this->createMock(AgentRepository::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->lockFactory = $this->createMock(LockFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->kernel = $this->createMock(KernelInterface::class);
        $this->serverMessageRepository = $this->createMock(ServerMessageRepository::class);
    }
    
    public function testIndexAction_withEchostrValidation(): void
    {
        $this->markTestSkipped('需要重构控制器测试，避免直接依赖 Encryptor');
    }
    
    public function testIndexAction_withCorpNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('找不到企业');
        
        $this->corpRepository->method('findOneBy')->willReturn(null);
        
        $request = new Request();
        
        $this->controller->index(
            'invalid_corp_id',
            'agent_id',
            $request,
            $this->corpRepository,
            $this->agentRepository,
            $this->eventDispatcher,
            $this->lockFactory,
            $this->logger
        );
    }
    
    public function testIndexAction_withAgentNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('找不到应用信息');
        
        $corp = $this->createMock(Corp::class);
        
        $this->corpRepository->method('findOneBy')->willReturn($corp);
        $this->agentRepository->method('findOneBy')->willReturn(null);
        
        $request = new Request();
        
        $this->controller->index(
            'corp_id',
            'invalid_agent_id',
            $request,
            $this->corpRepository,
            $this->agentRepository,
            $this->eventDispatcher,
            $this->lockFactory,
            $this->logger
        );
    }
    
    public function testDirectCallback(): void
    {
        $corpId = 'ww72805907153f7fa3';
        $content = '<xml><ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName><CreateTime>1654355183</CreateTime></xml>';
        
        $request = new Request([], [], [], [], [], [], $content);
        
        $this->kernel->method('getProjectDir')
            ->willReturn('/tmp');
        
        $response = $this->controller->directCallback(
            $corpId,
            $request,
            $this->kernel,
            $this->serverMessageRepository,
            $this->logger
        );
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('success', $response->getContent());
    }
    
    public function testDirectCallback_withRepositoryException(): void
    {
        $corpId = 'ww72805907153f7fa3';
        $content = '<xml><ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName><CreateTime>1654355183</CreateTime></xml>';
        
        $request = new Request([], [], [], [], [], [], $content);
        
        $this->kernel->method('getProjectDir')
            ->willReturn('/tmp');
        
        $exception = new \Exception('Database error');
        
        $this->serverMessageRepository->method('saveXML')
            ->willThrowException($exception);
        
        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                $this->equalTo('保存到数据库时发生错误'),
                $this->callback(function ($context) use ($exception) {
                    return isset($context['exception']) && $context['exception'] === $exception;
                })
            );
        
        $response = $this->controller->directCallback(
            $corpId,
            $request,
            $this->kernel,
            $this->serverMessageRepository,
            $this->logger
        );
        
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('success', $response->getContent());
    }
    
    public function testParseMessageWithXML(): void
    {
        $xml = '<xml><ToUserName><![CDATA[ww72805907153f7fa3]]></ToUserName><CreateTime>1654355183</CreateTime></xml>';
        
        $reflection = new \ReflectionClass(ServerController::class);
        $parseMessageMethod = $reflection->getMethod('parseMessage');
        $parseMessageMethod->setAccessible(true);
        
        $result = $parseMessageMethod->invoke($this->controller, $xml);
        
        $this->assertIsArray($result);
        $this->assertEquals('ww72805907153f7fa3', $result['ToUserName']);
        $this->assertEquals('1654355183', $result['CreateTime']);
    }
    
    public function testParseMessageWithJSON(): void
    {
        $json = '{"ToUserName":"ww72805907153f7fa3","CreateTime":"1654355183"}';
        
        $reflection = new \ReflectionClass(ServerController::class);
        $parseMessageMethod = $reflection->getMethod('parseMessage');
        $parseMessageMethod->setAccessible(true);
        
        $result = $parseMessageMethod->invoke($this->controller, $json);
        
        $this->assertIsArray($result);
        $this->assertEquals('ww72805907153f7fa3', $result['ToUserName']);
        $this->assertEquals('1654355183', $result['CreateTime']);
    }
    
    public function testParseMessageWithInvalidFormat(): void
    {
        $this->markTestSkipped('由于依赖于 XML::parse，无法测试 BadRequestException。需要修改控制器实现，使用依赖注入或隔离下层依赖');
    }
} 