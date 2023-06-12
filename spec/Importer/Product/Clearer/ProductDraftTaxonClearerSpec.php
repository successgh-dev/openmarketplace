<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Clearer;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftTaxon;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftTaxonClearer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftTaxonClearerSpec extends ObjectBehavior
{
    public function let(RepositoryInterface $productDraftTaxonRepository): void
    {
        $this->beConstructedWith($productDraftTaxonRepository);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftTaxonClearer::class);
    }

    public function it_clears_attribute_relations_from_product_draft(
        ProductDraftInterface $productDraft
    ): void {
        $productDraftTaxon = new ProductDraftTaxon();
        $productDraftTaxonCollection = new ArrayCollection([$productDraftTaxon]);
        $productDraft->addProductDraftTaxon($productDraftTaxon);

        $productDraft->getProductDraftTaxons()->willReturn($productDraftTaxonCollection);

        $productDraft->removeProductDraftTaxon($productDraftTaxon)->shouldBeCalled();

        $this->clear($productDraft);
    }
}
