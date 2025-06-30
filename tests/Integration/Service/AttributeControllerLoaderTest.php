<?php

namespace WechatWorkServerBundle\Tests\Integration\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouteCollection;
use WechatWorkServerBundle\Service\AttributeControllerLoader;

class AttributeControllerLoaderTest extends TestCase
{
    private AttributeControllerLoader $loader;

    protected function setUp(): void
    {
        $this->loader = new AttributeControllerLoader();
    }

    public function test_loader_can_be_instantiated(): void
    {
        $this->assertInstanceOf(AttributeControllerLoader::class, $this->loader);
    }

    public function test_supports_always_returns_false(): void
    {
        $this->assertFalse($this->loader->supports('any_resource'));
        $this->assertFalse($this->loader->supports('any_resource', 'any_type'));
        $this->assertFalse($this->loader->supports(null));
    }

    public function test_load_returns_route_collection(): void
    {
        $result = $this->loader->load('dummy_resource');

        $this->assertInstanceOf(RouteCollection::class, $result);
    }

    public function test_autoload_returns_route_collection_with_controllers(): void
    {
        $result = $this->loader->autoload();

        $this->assertInstanceOf(RouteCollection::class, $result);
        $this->assertGreaterThan(0, $result->count(), 'Route collection should contain routes');
    }

    public function test_autoload_includes_server_callback_routes(): void
    {
        $result = $this->loader->autoload();
        $routes = $result->all();
        
        $hasServerCallbackRoute = false;
        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_controller']) && str_contains($defaults['_controller'], 'ServerCallbackController')) {
                $hasServerCallbackRoute = true;
                break;
            }
        }

        $this->assertTrue($hasServerCallbackRoute, 'Route collection should include ServerCallbackController routes');
    }

    public function test_autoload_includes_direct_callback_routes(): void
    {
        $result = $this->loader->autoload();
        $routes = $result->all();
        
        $hasDirectCallbackRoute = false;
        foreach ($routes as $route) {
            $defaults = $route->getDefaults();
            if (isset($defaults['_controller']) && str_contains($defaults['_controller'], 'DirectCallbackController')) {
                $hasDirectCallbackRoute = true;
                break;
            }
        }

        $this->assertTrue($hasDirectCallbackRoute, 'Route collection should include DirectCallbackController routes');
    }

    public function test_loader_implements_routing_auto_loader_interface(): void
    {
        $reflection = new \ReflectionClass(AttributeControllerLoader::class);
        $interfaces = $reflection->getInterfaceNames();

        $this->assertContains(
            'Tourze\RoutingAutoLoaderBundle\Service\RoutingAutoLoaderInterface',
            $interfaces,
            'AttributeControllerLoader should implement RoutingAutoLoaderInterface'
        );
    }
}