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
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPriceInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

interface ProductListingPriceFactoryInterface extends FactoryInterface
{
    public function createWithData(
        string $channelCode,
        ProductDraftInterface $productDraft,
        int $price,
        int $minimumPrice,
        ?int $originalPrice,
        ): ProductListingPriceInterface;
}
