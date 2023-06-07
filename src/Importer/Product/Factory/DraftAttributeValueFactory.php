<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeValue;
use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeValueInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use Sylius\Component\Attribute\Model\AttributeInterface;

final class DraftAttributeValueFactory implements DraftAttributeValueFactoryInterface
{
    public function createNew(): DraftAttributeValueInterface
    {
        return new DraftAttributeValue();
    }

    public function createWithData(
        AttributeInterface $attribute,
        ProductDraftInterface $productDraft,
        string $localeCode,
        mixed $value
    ): DraftAttributeValueInterface {
        $draftAttributeValue = $this->createNew();
        $draftAttributeValue->setAttribute($attribute);
        $draftAttributeValue->setDraft($productDraft);
        $draftAttributeValue->setValue($value);
        $draftAttributeValue->setLocaleCode($localeCode);

        return $draftAttributeValue;
    }
}
