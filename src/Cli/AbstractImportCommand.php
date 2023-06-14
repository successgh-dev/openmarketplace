<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Cli;

use BitBag\SyliusCmsPlugin\Processor\ImportProcessorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Stopwatch\Stopwatch;

abstract class AbstractImportCommand extends Command
{
    protected static string $resourceName;

    protected static $defaultDescription = 'Abstract importer command for Sylius resources';

    public function __construct(private ImportProcessorInterface $importProcessor)
    {
        parent::__construct();
    }

    public function getResourceName(): string
    {
        return static::$resourceName;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $filepath = $input->getArgument('filepath');

        if (false === file_exists($filepath)) {
            $output->writeln('Invalid file path for the imported file.');

            return Command::INVALID;
        }

        $stopwatch->start('import');

        /** @phpstan-ignore-next-line  */
        $this->importProcessor->processBatch($this->getResourceName(), $filepath, (int) $input->getOption('batch-size'));

        $event = $stopwatch->stop('import');

        $output->writeln(\sprintf(
            'Import finished, debug data: Duration: %.2f ms / Memory: %.2f MB',
            $event->getDuration(),
            $event->getMemory() / (1024 ** 2)
        ));

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription ?? '')
            ->addArgument(
                'filepath',
                InputArgument::REQUIRED,
                'Path to the CSV file containing the import data'
            )
            ->addOption(
                'batch-size',
                'b',
                InputOption::VALUE_OPTIONAL,
                'Sets the amount of products persisted to database before running the cleanup command',
                25
            )
        ;
    }
}
