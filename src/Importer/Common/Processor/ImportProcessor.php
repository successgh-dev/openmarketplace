<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Common\Processor;

use BitBag\SyliusCmsPlugin\Exception\ImportFailedException;
use BitBag\SyliusCmsPlugin\Importer\ImporterChainInterface;
use BitBag\SyliusCmsPlugin\Processor\ImportProcessorInterface;
use BitBag\SyliusCmsPlugin\Reader\ReaderInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ImportProcessor implements ImportProcessorInterface
{
    public function __construct(
        private ImporterChainInterface $importerChain,
        private ReaderInterface $reader,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function process(string $resourceCode, string $filePath): void
    {
        $importer = $this->importerChain->getImporterForResource($resourceCode);
        $data = $this->reader->read($filePath);

        foreach ($data as $index => $row) {
            try {
                $importer->import($row);
            } catch (\Exception $exception) {
                $index += 1;

                throw new ImportFailedException($exception->getMessage(), $index);
            }

            $this->entityManager->clear();
        }

        $importer->cleanup();
    }

    public function processBatch(
        string $resourceCode,
        string $filePath,
        int $batchSize = 25
    ): void {
        $importer = $this->importerChain->getImporterForResource($resourceCode);
        $data = $this->reader->read($filePath);

        foreach ($data as $index => $row) {
            try {
                $importer->import($row);

                if (0 === $index) {
                    continue;
                }

                if (0 === $index % $batchSize) {
                    $this->entityManager->flush();
                    $this->entityManager->clear();
                }
            } catch (\Exception $exception) {
                $index += 1;

                throw new ImportFailedException($exception->getMessage(), $index);
            }
        }

        $importer->cleanup();
    }
}
