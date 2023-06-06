<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;

final class ProductDraftAttributeHandler extends AbstractAttributeHandler
{
    protected static string $dataKey = 'attributes';

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row) ||
            null === $vendor
        ) {
            return;
        }

        foreach (json_decode($row[self::$dataKey], true) as $index => $attributeData) {
            $this->handleProductAttribute(
                $attributeData['code'],
                $attributeData['type'],
                $attributeData['value'],
                $vendor,
                $productDraft
            );
        }
    }
}
