<?php

namespace WechatWorkServerBundle\Tests\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Tourze\PHPUnitSymfonyKernelTest\AbstractCommandTestCase;
use WechatWorkServerBundle\Command\ImportServerMessageCommand;

/**
 * @internal
 */
#[CoversClass(ImportServerMessageCommand::class)]
#[RunTestsInSeparateProcesses]
final class ImportServerMessageCommandTest extends AbstractCommandTestCase
{
    private ImportServerMessageCommand $command;

    private CommandTester $commandTester;

    protected function onSetUp(): void
    {
        $this->command = self::getService(ImportServerMessageCommand::class);

        $application = new Application();
        $application->add($this->command);

        $this->commandTester = new CommandTester($this->command);
    }

    protected function getCommandTester(): CommandTester
    {
        return $this->commandTester;
    }

    public function testCommandCreationSuccess(): void
    {
        $this->assertInstanceOf(Command::class, $this->command);
        $this->assertInstanceOf(ImportServerMessageCommand::class, $this->command);
    }

    public function testCommandExtendsSymfonyCommand(): void
    {
        $reflection = new \ReflectionClass($this->command);

        $this->assertTrue($reflection->isSubclassOf(Command::class));
    }

    public function testCommandName(): void
    {
        $this->assertEquals('wechat-work:import-server-message', $this->command->getName());
    }

    public function testCommandDescription(): void
    {
        $this->assertEquals('导入本地文件', $this->command->getDescription());
    }

    public function testCommandHasFileArgument(): void
    {
        $definition = $this->command->getDefinition();

        $this->assertTrue($definition->hasArgument('file'));

        $argument = $definition->getArgument('file');
        $this->assertEquals('LOG文件', $argument->getDescription());
        $this->assertFalse($argument->isRequired());
    }

    public function testExecuteWithoutArguments(): void
    {
        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function testExecuteWithFileArgument(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => 'test.log',
        ]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function testExecuteReturnsSuccess(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => 'nonexistent.log',
        ]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function testCommandNamespace(): void
    {
        $reflection = new \ReflectionClass($this->command);

        $this->assertEquals('WechatWorkServerBundle\Command', $reflection->getNamespaceName());
        $this->assertEquals('ImportServerMessageCommand', $reflection->getShortName());
    }

    public function testCommandConfigureMethodExists(): void
    {
        $reflection = new \ReflectionClass($this->command);

        $this->assertTrue($reflection->hasMethod('configure'));

        $method = $reflection->getMethod('configure');
        $this->assertTrue($method->isProtected());
    }

    public function testCommandExecuteMethodExists(): void
    {
        $reflection = new \ReflectionClass($this->command);

        $this->assertTrue($reflection->hasMethod('execute'));

        $method = $reflection->getMethod('execute');
        $this->assertTrue($method->isProtected());
    }

    public function testExecuteWithEmptyStringFile(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => '',
        ]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function testExecuteWithNullFile(): void
    {
        $exitCode = $this->commandTester->execute([]);

        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function testArgumentFile(): void
    {
        $definition = $this->command->getDefinition();

        $this->assertTrue($definition->hasArgument('file'));

        $argument = $definition->getArgument('file');
        $this->assertEquals('LOG文件', $argument->getDescription());
        $this->assertFalse($argument->isRequired());
        $this->assertNull($argument->getDefault());
    }
}
