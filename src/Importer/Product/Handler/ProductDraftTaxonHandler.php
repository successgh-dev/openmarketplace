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
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftTaxonInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Importer\Common\Trait\ImporterTaxonAwareTrait;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class ProductDraftTaxonHandler extends AbstractHandler
{
    use ImporterTaxonAwareTrait;

    protected static string $dataKey = 'category_code';

    public const SHOW_IN_ALL_CATEGORIES = 'show_in_all_categories';

    public function __construct(
        private RepositoryInterface $taxonRepository,
        private FactoryInterface $productDraftTaxonFactory,
        private RepositoryInterface $productDraftTaxonRepository
    ) {
    }

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row)) {
            return;
        }

        /** @var TaxonInterface|null $taxon */
        $taxon = $this->taxonRepository->findOneBy(['code' => $row[self::$dataKey]]);
        Assert::notNull($taxon, \sprintf('Couldn\'t find taxon with code: %s', $row[self::$dataKey]));

        foreach ($productDraft->getProductDraftTaxons() as $productDraftTaxon) {
            $productDraft->removeProductDraftTaxon($productDraftTaxon);
            $this->productDraftTaxonRepository->remove($productDraftTaxon);
        }

        $showProductInAllCategories =
            array_key_exists(self::SHOW_IN_ALL_CATEGORIES, $row) &&
            'true' === $row[self::SHOW_IN_ALL_CATEGORIES];

        $this->handleProductTaxonTree($productDraft, $taxon, $showProductInAllCategories);

        $productDraft->setMainTaxon($taxon);
    }

    private function handleProductTaxonTree(
        ProductDraftInterface $productDraft,
        TaxonInterface $taxon,
        bool $showProductInAllCategories
    ): void {
        /** @var TaxonInterface|null $taxonParent */
        $taxonParent = $taxon->getParent();

        if (true === $showProductInAllCategories &&
            null !== $taxonParent &&
            $this->mainTaxonCode !== $taxonParent->getCode()) {
            $this->handleProductTaxonTree($productDraft, $taxonParent, true);
        }

        /** @var ProductDraftTaxonInterface $productDraftTaxon */
        $productDraftTaxon = $this->productDraftTaxonFactory->createNew();
        $productDraftTaxon->setProductDraft($productDraft);
        $productDraftTaxon->setTaxon($taxon);

        $productDraft->addProductDraftTaxon($productDraftTaxon);
    }
}
