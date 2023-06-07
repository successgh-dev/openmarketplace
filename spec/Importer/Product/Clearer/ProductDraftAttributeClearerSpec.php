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
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftAttributeClearer;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Attribute\Model\AttributeValue;

final class ProductDraftAttributeClearerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftAttributeClearer::class);
    }

    public function it_clears_attribute_relations_from_product_draft(
        ProductDraftInterface $productDraft
    ): void {
        $attribute = new AttributeValue();
        $attributeCollection = new ArrayCollection([$attribute]);
        $productDraft->addAttribute($attribute);

        $productDraft->getAttributes()->willReturn($attributeCollection);

        $productDraft->removeAttribute($attribute)->shouldBeCalled();

        $this->clear($productDraft);
    }
}
