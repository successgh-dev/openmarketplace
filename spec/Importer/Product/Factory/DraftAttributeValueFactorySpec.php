<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeValueInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeValueFactory;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Attribute\Model\AttributeInterface;

final class DraftAttributeValueFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DraftAttributeValueFactory::class);
    }

    public function it_returns_valid_draft_attribute(): void
    {
        $this->createNew()->shouldBeAnInstanceOf(DraftAttributeValueInterface::class);
    }

    public function it_returns_valid_draft_attribute_with_data(
        AttributeInterface $attribute,
        ProductDraftInterface $productDraft
    ): void {
        $attribute->getStorageType()->willReturn('text');

        $draftAttribute = $this->createWithData($attribute, $productDraft, 'locale', 'value');

        $draftAttribute->getAttribute()->shouldBeEqualTo($attribute);
        $draftAttribute->getSubject()->shouldBeEqualTo($productDraft);
        $draftAttribute->getValue()->shouldBeEqualTo('value');
        $draftAttribute->getLocaleCode()->shouldBeEqualTo('locale');
    }
}
