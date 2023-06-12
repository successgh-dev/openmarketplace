<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\DraftAttributeFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeTranslationFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeValueFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftAttributeHandler extends AbstractAttributeHandler
{
    public function __construct(
        RepositoryInterface $draftAttributeRepository,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext,
        private ProductDraftRelationsClearerInterface $productDraftAttributeClearer
    ) {
        parent::__construct($draftAttributeRepository, $draftAttributeFactory, $draftAttributeTranslationFactory, $draftAttributeValueFactory, $entityManager, $localeContext);
    }

    protected static string $dataKey = 'attributes';

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row) ||
            null === $vendor
        ) {
            return;
        }

        $this->productDraftAttributeClearer->clear($productDraft);

        foreach (json_decode($row[self::$dataKey], true) as $attributeData) {
            $this->handleProductAttribute(
                $attributeData['code'],
                $attributeData['type'],
                $attributeData['value'],
                $vendor,
                $productDraft
            );
        }
    }
}
