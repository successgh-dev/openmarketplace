<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Common\Reader;

use BitBag\SyliusCmsPlugin\Reader\ReaderInterface;
use League\Csv\Reader;

final class CsvReader implements ReaderInterface
{
    public function read(string $filePath): \Iterator
    {
        return Reader::createFromPath($filePath, 'r')
            ->setDelimiter('|')
            ->setHeaderOffset(0)
            ->getIterator();
    }
}
