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
use Psr\Log\LoggerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;

final class ProductDraftShippingHandler extends AbstractHandler
{
    public function __construct(
        private RepositoryInterface $shippingCategoryRepository,
        private LoggerInterface $logger
    ) {
    }

    protected static string $dataKey = 'shipping_category';

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row)) {
            return;
        }

        $shippingCategoryCode = $row[self::$dataKey];

        /** @var ShippingCategoryInterface|null $shippingCategory */
        $shippingCategory = $this->shippingCategoryRepository->findOneBy(['code' => $shippingCategoryCode]);

        if (null === $shippingCategory) {
            $this->logger->warning(
                \sprintf('Couldn\'t find shipping category with code %s', $shippingCategoryCode)
            );

            return;
        }

        $productDraft->setShippingRequired(true);
        $productDraft->setShippingCategory($shippingCategory);
    }
}
