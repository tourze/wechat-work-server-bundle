<?php

declare(strict_types=1);

namespace WechatWorkServerBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;
use WechatWorkServerBundle\WechatWorkServerBundle;

/**
 * @internal
 */
#[CoversClass(WechatWorkServerBundle::class)]
#[RunTestsInSeparateProcesses]
final class WechatWorkServerBundleTest extends AbstractBundleTestCase
{
}
