<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeTranslationInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeTranslationFactory;
use PhpSpec\ObjectBehavior;

final class DraftAttributeTranslationFactorySpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(DraftAttributeTranslationFactory::class);
    }

    public function it_returns_valid_draft_attribute_translation(): void
    {
        $this->createNew()->shouldBeAnInstanceOf(DraftAttributeTranslationInterface::class);
    }

    public function it_returns_valid_draft_attribute_translation_with_data(
        DraftAttributeInterface $draftAttribute
    ): void {
        $draftAttributeTranslation = $this->createWithData('name', 'locale', $draftAttribute);

        $draftAttributeTranslation->getName()->shouldBeEqualTo('name');
        $draftAttributeTranslation->getLocale()->shouldBeEqualTo('locale');
        $draftAttributeTranslation->getTranslatable()->shouldBeEqualTo($draftAttribute);
    }
}
