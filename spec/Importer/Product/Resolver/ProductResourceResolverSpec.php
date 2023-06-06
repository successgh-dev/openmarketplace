<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Resolver;

use BitBag\OpenMarketplace\Factory\ProductListingFromDraftFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Resolver\ProductResourceResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductResourceResolverSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $repository,
        FactoryInterface $factory,
        ProductListingFromDraftFactoryInterface $productListingFromDraftFactory
    ) {
        $this->beConstructedWith(
            $repository,
            $factory,
            $productListingFromDraftFactory
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductResourceResolver::class);
    }
}
