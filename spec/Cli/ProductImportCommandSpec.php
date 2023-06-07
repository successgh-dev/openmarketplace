<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Cli;

use BitBag\OpenMarketplace\Cli\ProductImportCommand;
use BitBag\SyliusCmsPlugin\Processor\ImportProcessorInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Command\Command;

final class ProductImportCommandSpec extends ObjectBehavior
{
    public function let(ImportProcessorInterface $importProcessor): void
    {
        $this->beConstructedWith($importProcessor);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductImportCommand::class);
    }

    public function it_is_a_command(): void
    {
        $this->shouldBeAnInstanceOf(Command::class);
    }

    public function it_should_contain_product_import_related_properties(): void
    {
        $this->getResourceName()->shouldBe('product');
        $this->getName()->shouldBe('open-marketplace:product:import');
    }
}
