<?php

namespace WechatWorkServerBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[AsCommand(name: self::NAME, description: '导入本地文件')]
#[Autoconfigure(public: true)]
class ImportServerMessageCommand extends Command
{
    public const NAME = 'wechat-work:import-server-message';
    //    public function __construct(private readonly ServerMessageRepository $messageRepository)
    //    {
    //        parent::__construct();
    //    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::OPTIONAL, 'LOG文件');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //        if (!is_file($input->getArgument('file'))) {
        //            $output->writeln('文件不存在');
        //            return Command::FAILURE;
        //        }
        //
        //        $success = 0;
        //        $lines = file($input->getArgument('file'));
        //        foreach ($lines as $line) {
        //            $line = trim($line);
        //            if (empty($line)) {
        //                continue;
        //            }
        //
        //            try {
        //                $message = $this->messageRepository->saveXML($line);
        //                $message && $success++;
        //            } catch (\Throwable $exception) {
        //                $output->writeln(sprintf("导入数据库时发生异常：%s。当前导入的数据是：%s", $exception, $line));
        //            }
        //        }
        //        $this->messageRepository->flush();
        //
        //        $output->writeln(sprintf("成功入库数量：%d", $success));
        return Command::SUCCESS;
    }
}
