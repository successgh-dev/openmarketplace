<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Common\Reader;

use BitBag\OpenMarketplace\Importer\Common\Reader\CsvReader;
use BitBag\SyliusCmsPlugin\Reader\ReaderInterface;
use PhpSpec\ObjectBehavior;

final class CsvReaderSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(CsvReader::class);
    }

    public function it_implements_reader_interface(): void
    {
        $this->shouldImplement(ReaderInterface::class);
    }

    public function it_reads_csv_file_and_returns_iterator(): void
    {
        $filePath = 'spec/Importer/Common/Reader/testfiles/test.csv';

        $this->read($filePath)
            ->shouldReturnAnInstanceOf(\Iterator::class);
    }
}
