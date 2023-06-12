<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Clearer;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftTaxonClearer implements ProductDraftRelationsClearerInterface
{
    public function __construct(private RepositoryInterface $productDraftTaxonRepository)
    {
    }

    public function clear(ProductDraftInterface $productDraft): void
    {
        foreach ($productDraft->getProductDraftTaxons() as $productDraftTaxons) {
            $productDraft->removeProductDraftTaxon($productDraftTaxons);
            $this->productDraftTaxonRepository->remove($productDraftTaxons);
        }
    }
}
