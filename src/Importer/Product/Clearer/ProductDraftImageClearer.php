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
use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftImageClearer implements ProductDraftRelationsClearerInterface
{
    public function __construct(private RepositoryInterface $productDraftImagesRepository)
    {
    }

    public function clear(ProductDraftInterface $productDraft): void
    {
        foreach ($productDraft->getImages() as $image) {
            $productDraft->removeImage($image);
            $this->productDraftImagesRepository->remove($image);
        }

        $productDraft->setImages(new ArrayCollection());
    }
}
