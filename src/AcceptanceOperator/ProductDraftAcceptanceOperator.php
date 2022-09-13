<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\AcceptanceOperator;

use BitBag\SyliusMultiVendorMarketplacePlugin\Converter\AttributesConverter;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductInterface as BitBagProductInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductListing\ProductDraftInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Factory\ProductFromDraftFactoryInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Operator\ProductDraftFilesOperatorInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Updater\ProductFromDraftUpdaterInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ProductDraftAcceptanceOperator implements ProductDraftAcceptanceOperatorInterface
{
    private ProductFromDraftFactoryInterface $productFromDraftFactory;

    private ProductFromDraftUpdaterInterface $productFromDraftUpdater;

    private ProductDraftFilesOperatorInterface $productDraftFilesOperator;

    private AttributesConverter $attributesConverter;

    public function __construct(
        ProductFromDraftFactoryInterface $productFromDraftFactory,
        ProductFromDraftUpdaterInterface $productFromDraftUpdater,
        ProductDraftFilesOperatorInterface $productDraftFilesOperator,
        AttributesConverter $attributesConverter
    ) {
        $this->productFromDraftFactory = $productFromDraftFactory;
        $this->productFromDraftUpdater = $productFromDraftUpdater;
        $this->productDraftFilesOperator = $productDraftFilesOperator;
        $this->attributesConverter = $attributesConverter;
    }

    public function acceptProductDraft(ProductDraftInterface $productDraft): ProductInterface
    {
        if (!$productDraft->getProductListing()->getProduct()) {
            $cratedProduct = $this->productFromDraftFactory->createSimpleProduct($productDraft);
            $this->productDraftFilesOperator->copyFilesToProduct($productDraft, $cratedProduct);
            $this->attributesConverter->convert($productDraft, $cratedProduct);

            return $cratedProduct;
        }

        /** @var BitBagProductInterface $product */
        $product = $this->productFromDraftUpdater->updateProduct($productDraft);

        $this->productDraftFilesOperator->removeOldFiles($product);
        $this->productDraftFilesOperator->copyFilesToProduct($productDraft, $product);
        $this->attributesConverter->convert($productDraft, $product);

        return $product;
    }
}
