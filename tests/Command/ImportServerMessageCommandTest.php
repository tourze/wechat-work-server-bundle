<?php

namespace WechatWorkServerBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use WechatWorkServerBundle\Command\ImportServerMessageCommand;

class ImportServerMessageCommandTest extends TestCase
{
    private ImportServerMessageCommand $command;
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        $this->command = new ImportServerMessageCommand();
        
        $application = new Application();
        $application->add($this->command);
        
        $this->commandTester = new CommandTester($this->command);
    }

    public function test_command_creation_success(): void
    {
        $this->assertInstanceOf(Command::class, $this->command);
        $this->assertInstanceOf(ImportServerMessageCommand::class, $this->command);
    }

    public function test_command_extends_symfony_command(): void
    {
        $reflection = new \ReflectionClass($this->command);
        
        $this->assertTrue($reflection->isSubclassOf(Command::class));
    }

    public function test_command_name(): void
    {
        $this->assertEquals('wechat-work:import-server-message', $this->command->getName());
    }

    public function test_command_description(): void
    {
        $this->assertEquals('导入本地文件', $this->command->getDescription());
    }

    public function test_command_has_file_argument(): void
    {
        $definition = $this->command->getDefinition();
        
        $this->assertTrue($definition->hasArgument('file'));
        
        $argument = $definition->getArgument('file');
        $this->assertEquals('LOG文件', $argument->getDescription());
        $this->assertFalse($argument->isRequired());
    }

    public function test_execute_without_arguments(): void
    {
        $exitCode = $this->commandTester->execute([]);
        
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function test_execute_with_file_argument(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => 'test.log'
        ]);
        
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function test_execute_returns_success(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => 'nonexistent.log'
        ]);
        
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function test_command_namespace(): void
    {
        $reflection = new \ReflectionClass($this->command);
        
        $this->assertEquals('WechatWorkServerBundle\Command', $reflection->getNamespaceName());
        $this->assertEquals('ImportServerMessageCommand', $reflection->getShortName());
    }

    public function test_command_configure_method_exists(): void
    {
        $reflection = new \ReflectionClass($this->command);
        
        $this->assertTrue($reflection->hasMethod('configure'));
        
        $method = $reflection->getMethod('configure');
        $this->assertTrue($method->isProtected());
    }

    public function test_command_execute_method_exists(): void
    {
        $reflection = new \ReflectionClass($this->command);
        
        $this->assertTrue($reflection->hasMethod('execute'));
        
        $method = $reflection->getMethod('execute');
        $this->assertTrue($method->isProtected());
    }

    public function test_execute_with_empty_string_file(): void
    {
        $exitCode = $this->commandTester->execute([
            'file' => ''
        ]);
        
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }

    public function test_execute_with_null_file(): void
    {
        $exitCode = $this->commandTester->execute([]);
        
        $this->assertEquals(Command::SUCCESS, $exitCode);
    }
} 