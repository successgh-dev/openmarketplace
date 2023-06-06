<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Cli;

use BitBag\SyliusCmsPlugin\Processor\ImportProcessorInterface;

final class ProductImportCommand extends AbstractImportCommand
{
    protected static string $resourceName = 'product';

    protected static $defaultName = 'open-marketplace:product:import';

    protected static $defaultDescription = 'Imports product data from external source into OpenMarketplace';

    public function __construct(private ImportProcessorInterface $importProcessor)
    {
        parent::__construct($this->importProcessor);
    }
}
