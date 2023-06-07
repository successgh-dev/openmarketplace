<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPriceInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\ProductListingPriceFactory;
use PhpSpec\ObjectBehavior;

final class ProductListingPriceFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductListingPriceFactory::class);
    }

    public function it_returns_valid_product_listing_price(): void
    {
        $this->createNew()->shouldBeAnInstanceOf(ProductListingPriceInterface::class);
    }

    public function it_returns_valid_product_listing_price_with_data(
        ProductDraftInterface $productDraft
    ): void {
        $productListingPrice = $this->createWithData(
            'channelCode',
            $productDraft,
            1000,
            100,
            null
        );

        $productListingPrice->getChannelCode()->shouldBeEqualTo('channelCode');
        $productListingPrice->getProductDraft()->shouldBeEqualTo($productDraft);
        $productListingPrice->getPrice()->shouldBeEqualTo(1000);
        $productListingPrice->getMinimumPrice()->shouldBeEqualTo(100);
        $productListingPrice->getOriginalPrice()->shouldBeEqualTo(null);
    }
}
