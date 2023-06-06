<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftTaxonHandler;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftTaxonHandlerSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $taxonRepository,
        FactoryInterface $productDraftTaxonFactory,
        RepositoryInterface $productDraftTaxonRepository
    ) {
        $this->beConstructedWith(
            $taxonRepository,
            $productDraftTaxonFactory,
            $productDraftTaxonRepository
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftTaxonHandler::class);
    }
}
