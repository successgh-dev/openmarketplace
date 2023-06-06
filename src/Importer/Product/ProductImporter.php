<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product;

use BitBag\OpenMarketplace\AcceptanceOperator\ProductDraftAcceptanceOperatorInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductHandlerInterface;
use BitBag\OpenMarketplace\Importer\Product\Resolver\ProductResourceResolverInterface;
use BitBag\SyliusCmsPlugin\Importer\AbstractImporter;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class ProductImporter extends AbstractImporter implements ProductImporterInterface
{
    public function __construct(
        private ProductResourceResolverInterface $productDraftResourceResolver,
        private RepositoryInterface $vendorRepository,
        private EntityManagerInterface $entityManager,
        private ProductDraftAcceptanceOperatorInterface $productDraftService,
        private iterable $productDraftRelationClearers,
        private iterable $productDraftDataHandlers
    ) {
    }

    public function import(array $row): void
    {
        /** @var string $code */
        $code = $this->getColumnValue(self::CODE_COLUMN, $row);

        /** @var VendorInterface $vendor */
        $vendor = $this->vendorRepository->findOneBy(['uuid' => $row['vendor_id']]);
        Assert::notNull($vendor, \sprintf('Cannot find a vendor with given vendor_id: %s.', $row['vendor_id']));

        /** @var ProductDraftInterface $productDraft */
        $productDraft = $this->productDraftResourceResolver->getResourceByCodeAndVendor($code, $vendor);

        $productListing = $productDraft->getProductListing();

        $product = $productListing->getProduct();

        if (null !== $product && null === $product->getVendor()) {
            $product->setVendor($vendor);
        }

        $isEnabled = (array_key_exists('enabled', $row) && 'enabled' === $row['enabled']);
        $productListing->setEnabled($isEnabled);

        $this->clearRelations($productDraft);

        /** @var ProductHandlerInterface $handler */
        foreach ($this->productDraftDataHandlers as $handler) {
            $handler->handle($productDraft, $row, $vendor);
        }

        $isAutoVerified = (false !== array_key_exists('enabled', $row) && 'true' === $row[self::AUTO_VERIFY]);

        if ($isAutoVerified) {
            $productListing->sendToVerification($productDraft);
            //TODO SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'product_code-vendor_id' for key 'sylius_product.UNIQ_' (sylius_product.code)
//            $this->productDraftService->acceptProductDraft($productDraft);
        }

        $this->entityManager->persist($productDraft);
        $this->entityManager->flush();
    }

    public function getResourceCode(): string
    {
        return 'product';
    }

    public function clearRelations(ProductDraftInterface $productDraft): void
    {
        /** @var ProductDraftRelationsClearerInterface $productDraftRelationClearer */
        foreach ($this->productDraftRelationClearers as $productDraftRelationClearer) {
            $productDraftRelationClearer->clear($productDraft);
        }
    }
}
