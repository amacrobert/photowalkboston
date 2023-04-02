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
    name: 'app:use-internet',
    description: 'Add a short description for your command',
)]
class UseInternetCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->addArgument('address', InputArgument::REQUIRED, 'A URL to get');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = file_get_contents($input->getArgument('address'));

        $output->writeln($result . PHP_EOL);

        return Command::SUCCESS;
    }
}
