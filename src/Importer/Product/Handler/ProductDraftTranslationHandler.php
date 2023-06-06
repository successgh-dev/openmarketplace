<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductTranslationInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Repository\ProductListing\ProductTranslationRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductDraftTranslationHandler extends AbstractHandler
{
    private const NAME_KEY = 'name';

    private const DESCRIPTION_KEY = 'description';

    private const URL_KEY = 'url';

    public function __construct(
        private FactoryInterface $productTranslationFactory,
        private ProductTranslationRepositoryInterface $productDraftTranslationRepository,
        private LocaleContextInterface $localeContext,
        private SlugGeneratorInterface $slugGenerator
    ) {
    }

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::NAME_KEY, $row) &&
            false === $this->supports(self::DESCRIPTION_KEY, $row) &&
            false === $this->supports(self::URL_KEY, $row)
        ) {
            return;
        }

        $importSlugValue = '' !== $row[self::URL_KEY] ? $row[self::URL_KEY] : $row[self::NAME_KEY];
        $slug = $this->slugGenerator->generate($importSlugValue);
        $localeCode = $this->localeContext->getLocaleCode();

        /** @var ProductTranslationInterface|null $productDraftTranslation */
        $productDraftTranslation = $this->productDraftTranslationRepository->findOneBy([
            'productDraft' => $productDraft,
            'locale' => $localeCode,
        ]);

        if (null === $productDraftTranslation) {
            /** @var ProductTranslationInterface $productDraftTranslation */
            $productDraftTranslation = $this->productTranslationFactory->createNew();
        }

        $productDraftTranslation->setName($row[self::NAME_KEY]);
        $productDraftTranslation->setDescription($row[self::DESCRIPTION_KEY]);
        $productDraftTranslation->setSlug($slug);
        $productDraftTranslation->setLocale($localeCode);
        $productDraftTranslation->setShortDescription(null);
        $productDraftTranslation->setMetaDescription(null);
        $productDraftTranslation->setMetaKeywords(null);

        if (null === $productDraftTranslation->getId()) {
            $productDraftTranslation->setProductDraft($productDraft);
            $productDraft->addTranslation($productDraftTranslation);
        }
    }
}
