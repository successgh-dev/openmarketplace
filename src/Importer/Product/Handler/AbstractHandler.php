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

abstract class AbstractHandler implements ProductHandlerInterface
{
    protected static string $dataKey;

    protected function supports(string $key, array $row): bool
    {
        return array_key_exists($key, $row);
    }

    abstract public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void;
}
