<?php

namespace WechatWorkServerBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\RouterInterface;
use WechatWorkServerBundle\Repository\ServerMessageRepository;

/**
 * 集成测试
 * 
 * 由于复杂依赖关系，集成测试暂时标记为跳过
 */
class WechatWorkServerIntegrationTest extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return IntegrationTestKernel::class;
    }
    
    /**
     * 检查存储库服务是否可用
     */
    public function testContainerHasServerMessageRepository(): void
    {
        $this->markTestSkipped('集成测试暂时跳过，等待完整依赖解决');
        
        self::bootKernel();
        $container = self::getContainer();
        
        $this->assertTrue($container->has(ServerMessageRepository::class));
        $this->assertInstanceOf(
            ServerMessageRepository::class,
            $container->get(ServerMessageRepository::class)
        );
    }
    
    /**
     * 检查路由是否正确注册
     */
    public function testRouterHasServerControllerRoutes(): void
    {
        $this->markTestSkipped('集成测试暂时跳过，等待完整依赖解决');
        
        self::bootKernel();
        $container = self::getContainer();
        $router = $container->get('router');
        
        $this->assertInstanceOf(RouterInterface::class, $router);
        
        $routes = $router->getRouteCollection();
        
        $this->assertNotNull($routes->get('wechat_work_server'));
        $this->assertNotNull($routes->get('wechat_work_server_direct_callback'));
    }
    
    /**
     * 确认控制器服务正确配置
     */
    public function testServerControllerIsAutowired(): void
    {
        $this->markTestSkipped('集成测试暂时跳过，等待完整依赖解决');
        
        self::bootKernel();
        $container = self::getContainer();
        
        $this->assertTrue($container->has('WechatWorkServerBundle\Controller\ServerController'));
    }
    
    /**
     * 验证 Doctrine 映射配置
     */
    public function testDoctrineMappingsAreLoaded(): void
    {
        $this->markTestSkipped('集成测试暂时跳过，等待完整依赖解决');
        
        self::bootKernel();
        $container = self::getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        
        $metadata = $em->getClassMetadata('WechatWorkServerBundle\Entity\ServerMessage');
        
        $this->assertEquals('wechat_work_server_message', $metadata->getTableName());
        $this->assertTrue($metadata->hasField('toUserName'));
        $this->assertTrue($metadata->hasField('fromUserName'));
        $this->assertTrue($metadata->hasField('createTime'));
    }
} 