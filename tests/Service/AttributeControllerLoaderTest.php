<?php

namespace WechatWorkServerBundle\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Routing\RouteCollection;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use WechatWorkServerBundle\Service\AttributeControllerLoader;

/**
 * @internal
 */
#[CoversClass(AttributeControllerLoader::class)]
#[RunTestsInSeparateProcesses]
final class AttributeControllerLoaderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
    }

    private function getLoader(): AttributeControllerLoader
    {
        return self::getService(AttributeControllerLoader::class);
    }

    public function testLoaderCanBeInstantiated(): void
    {
        $loader = $this->getLoader();
        $this->assertInstanceOf(AttributeControllerLoader::class, $loader);
    }

    public function testSupportsAlwaysReturnsFalse(): void
    {
        $loader = $this->getLoader();
        $this->assertFalse($loader->supports('any_resource'));
        $this->assertFalse($loader->supports('any_resource', 'any_type'));
        $this->assertFalse($loader->supports(null));
    }

    public function testLoadReturnsRouteCollection(): void
    {
        $loader = $this->getLoader();
        $result = $loader->load('dummy_resource');

        $this->assertInstanceOf(RouteCollection::class, $result);
    }

    public function testAutoloadReturnsRouteCollectionWithControllers(): void
    {
        $loader = $this->getLoader();
        $result = $loader->autoload();

        $this->assertInstanceOf(RouteCollection::class, $result);
        $this->assertGreaterThan(0, $result->count(), 'Route collection should contain routes');
    }

    public function testAutoloadIncludesServerCallbackRoutes(): void
    {
        $loader = $this->getLoader();
        $result = $loader->autoload();
        $routes = $result->all();

        $hasServerCallbackRoute = false;
        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_controller']) && is_string($defaults['_controller']) && str_contains($defaults['_controller'], 'ServerCallbackController')) {
                $hasServerCallbackRoute = true;
                break;
            }
        }

        $this->assertTrue($hasServerCallbackRoute, 'Route collection should include ServerCallbackController routes');
    }

    public function testAutoloadIncludesDirectCallbackRoutes(): void
    {
        $loader = $this->getLoader();
        $result = $loader->autoload();
        $routes = $result->all();

        $hasDirectCallbackRoute = false;
        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_controller']) && is_string($defaults['_controller']) && str_contains($defaults['_controller'], 'DirectCallbackController')) {
                $hasDirectCallbackRoute = true;
                break;
            }
        }

        $this->assertTrue($hasDirectCallbackRoute, 'Route collection should include DirectCallbackController routes');
    }

    public function testLoaderImplementsRoutingAutoLoaderInterface(): void
    {
        $loader = $this->getLoader();
        $reflection = new \ReflectionClass(AttributeControllerLoader::class);
        $interfaces = $reflection->getInterfaceNames();

        $this->assertContains(
            'Tourze\RoutingAutoLoaderBundle\Service\RoutingAutoLoaderInterface',
            $interfaces,
            'AttributeControllerLoader should implement RoutingAutoLoaderInterface'
        );
    }
}
