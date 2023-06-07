<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Clearer;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftImage;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftImageClearer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftImageClearerSpec extends ObjectBehavior
{
    public function let(RepositoryInterface $productDraftImagesRepository): void
    {
        $this->beConstructedWith($productDraftImagesRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftImageClearer::class);
    }

    public function it_clears_attribute_relations_from_product_draft(
        ProductDraftInterface $productDraft,
        RepositoryInterface $productDraftImagesRepository
    ): void {
        $image = new ProductDraftImage();
        $imageCollection = new ArrayCollection([$image]);
        $productDraft->addImage($image);

        $productDraft->getImages()->willReturn($imageCollection);

        $productDraft->removeImage($image)->shouldBeCalled();

        $productDraftImagesRepository->remove($image)->shouldBeCalled();

        $productDraft->setImages(new ArrayCollection())->shouldBeCalled();

        $this->clear($productDraft);
    }
}
