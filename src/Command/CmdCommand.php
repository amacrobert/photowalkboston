<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'cmd',
    description: 'Add a short description for your command',
)]
class CmdCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('input', InputArgument::IS_ARRAY, 'Command line input')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $commandArray = $input->getArgument('input');
        $command = implode(' ', $commandArray);

        $io = new SymfonyStyle($input, $output);

        $io->info($command);

        $commandOutput = [];
        $resultCode = null;

        exec($command, $commandOutput, $resultCode);
        $commandOutput = implode("\n", $commandOutput);

        if ($resultCode != 0) {
            $io->error('command exited with code ' . $resultCode);
            $io->error($commandOutput);
        } else {
            $io->success($commandOutput);
        }

        return $resultCode;
    }
}
