<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\ProductListingPriceFactoryInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

final class ProductDraftPriceHandler extends AbstractHandler
{
    protected static string $dataKey = 'prices';

    public function __construct(
        private ChannelRepositoryInterface $channelRepository,
        private ProductListingPriceFactoryInterface $productListingPriceFactory,
        private LoggerInterface $logger
    ) {
    }

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row)) {
            $this->logger->warning('No pricing data found in input file');

            return;
        }

        foreach (json_decode($row[self::$dataKey], true) as $channelCode => $pricingData) {
            $channel = $this->channelRepository->findOneByCode($channelCode);

            if (null === $channel) {
                continue;
            }

            $minimumPrice = (int) $pricingData['minimum_price'];
            $originalPrice = (int) $pricingData['original_price'];
            $price = (int) $pricingData['price'];

            $this->createProductListingPricing($productDraft, $channelCode, $price, $minimumPrice, $originalPrice);
        }
    }

    private function createProductListingPricing(
        ProductDraftInterface $productDraft,
        string $channelCode,
        int $price,
        int $minimumPrice,
        ?int $originalPrice
    ): void {
        $productListingPrice = $this->productListingPriceFactory->createWithData(
            $channelCode,
            $productDraft,
            $price,
            $minimumPrice,
            $originalPrice,
        );

        $productDraft->addProductListingPrice($productListingPrice);
    }
}
