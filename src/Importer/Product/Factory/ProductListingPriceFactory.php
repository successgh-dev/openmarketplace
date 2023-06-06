<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPrice;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPriceInterface;

final class ProductListingPriceFactory implements ProductListingPriceFactoryInterface
{
    public function createNew(): ProductListingPriceInterface
    {
        return new ProductListingPrice();
    }

    public function createWithData(
        string $channelCode,
        ProductDraftInterface $productDraft,
        int $price,
        int $minimumPrice,
        ?int $originalPrice
    ): ProductListingPriceInterface {
        $productListingPrice = $this->createNew();
        $productListingPrice->setChannelCode($channelCode);
        $productListingPrice->setPrice($price);
        $productListingPrice->setOriginalPrice($originalPrice);
        $productListingPrice->setMinimumPrice($minimumPrice);
        $productListingPrice->setProductDraft($productDraft);

        return $productListingPrice;
    }
}
