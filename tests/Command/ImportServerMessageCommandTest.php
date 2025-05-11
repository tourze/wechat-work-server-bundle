<?php

namespace WechatWorkServerBundle\Tests\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use WechatWorkServerBundle\Command\ImportServerMessageCommand;

class ImportServerMessageCommandTest extends TestCase
{
    private CommandTester $commandTester;
    
    protected function setUp(): void
    {
        $command = new ImportServerMessageCommand();
        
        $application = new Application();
        $application->add($command);
        
        $this->commandTester = new CommandTester($command);
    }
    
    public function testCommandDefinition(): void
    {
        $application = new Application();
        $command = new ImportServerMessageCommand();
        $application->add($command);
        
        $command = $application->find('wechat-work:import-server-message');
        
        $this->assertInstanceOf(ImportServerMessageCommand::class, $command);
        $this->assertEquals('wechat-work:import-server-message', $command->getName());
        $this->assertEquals('导入本地文件', $command->getDescription());
        
        $definition = $command->getDefinition();
        $this->assertTrue($definition->hasArgument('file'));
        $this->assertFalse($definition->getArgument('file')->isRequired());
    }
    
    public function testCommandExecution(): void
    {
        $this->commandTester->execute([]);
        
        $this->assertEquals(Command::SUCCESS, $this->commandTester->getStatusCode());
    }
    
    public function testCommandExecutionWithFile(): void
    {
        $this->commandTester->execute([
            'file' => 'non_existent_file.txt'
        ]);
        
        $this->assertEquals(Command::SUCCESS, $this->commandTester->getStatusCode());
    }
} 