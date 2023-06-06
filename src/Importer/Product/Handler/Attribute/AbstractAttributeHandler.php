<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\DraftAttributeFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeTranslationFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeValueFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\AbstractHandler;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

abstract class AbstractAttributeHandler extends AbstractHandler
{
    public function __construct(
        private RepositoryInterface $draftAttributeRepository,
        private DraftAttributeFactoryInterface $draftAttributeFactory,
        private DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        private DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        private EntityManagerInterface $entityManager,
        private LocaleContextInterface $localeContext
    ) {
    }

    protected function supports(string $key, array $row): bool
    {
        return array_key_exists($key, $row);
    }

    protected function handleProductAttribute(
        string $code,
        string $type,
        string $value,
        VendorInterface $vendor,
        ProductDraftInterface $productDraft
    ): void {
        $attribute = $this->resolveAttributeByCodeAndVendor($code, $vendor, $type);

        $attributeValue = $this->draftAttributeValueFactory->createWithData(
            $attribute,
            $productDraft,
            $this->localeContext->getLocaleCode(),
            $value
        );

        $productDraft->addAttribute($attributeValue);
    }

    private function resolveAttributeByCodeAndVendor(
        string $code,
        VendorInterface $vendor,
        string $attributeType = 'text'
    ): DraftAttributeInterface {
        /** @var DraftAttributeInterface|null $attribute */
        $attribute = $this->draftAttributeRepository->findOneBy([
            'code' => $code,
        ]);

        if (null === $attribute) {
            $attribute = $this->draftAttributeFactory->createTyped($attributeType, $vendor);
            $attribute->setCode($code);

            $this->draftAttributeTranslationFactory->createWithData(
                $code,
                $this->localeContext->getLocaleCode(),
                $attribute
            );

            $this->entityManager->persist($attribute);
        }

        return $attribute;
    }
}
