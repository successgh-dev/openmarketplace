<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Factory;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeTranslation;
use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeTranslationInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

final class DraftAttributeTranslationFactory implements DraftAttributeTranslationFactoryInterface
{
    public function createNew(): DraftAttributeTranslationInterface
    {
        return new DraftAttributeTranslation();
    }

    public function createWithData(
        string $name,
        string $locale,
        TranslatableInterface $translatable
    ): DraftAttributeTranslationInterface {
        $draftAttributeTranslation = $this->createNew();
        $draftAttributeTranslation->setName($name);
        $draftAttributeTranslation->setLocale($locale);
        $draftAttributeTranslation->setTranslatable($translatable);

        return $draftAttributeTranslation;
    }
}
