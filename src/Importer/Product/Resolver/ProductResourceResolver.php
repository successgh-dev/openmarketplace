<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Resolver;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductResourceResolver implements ProductResourceResolverInterface
{
    private const DISALLOWED_PRODUCT_DRAFT_STATUSES = [
        ProductDraftInterface::STATUS_UNDER_VERIFICATION,
        ProductDraftInterface::STATUS_VERIFIED,
    ];

    public function __construct(
        private RepositoryInterface $productListingRepository,
        private FactoryInterface $productDraftFactory,
        private FactoryInterface $productListingFactory
    ) {
    }

    public function getResource(string $identifier, string $factoryMethod = 'createNew'): ResourceInterface
    {
        /** @var ProductListingInterface|null $productListing */
        $productListing = $this->productListingRepository->findOneBy([
            'code' => $identifier,
        ]);

        if (null === $productListing) {
            /** @var ProductListingInterface $productListing */
            $productListing = $this->productListingFactory->createNew();
        }

        return $productListing;
    }

    public function getResourceByCodeAndVendor(
        string $codeIdentifier,
        VendorInterface $vendor,
        string $factoryMethod = 'createNew',
        array $arguments = [],
        ): ResourceInterface {
        /** @var ProductListingInterface|null $productListing */
        $productListing = $this->productListingRepository->findOneBy([
            'code' => $codeIdentifier,
            'vendor' => $vendor,
        ]);

        //TODO Marcin Kukliński will refactor this
        if (null !== $productListing?->getLatestDraft() &&
            false === in_array($productListing->getLatestDraft()->getStatus(), self::DISALLOWED_PRODUCT_DRAFT_STATUSES)
        ) {
            return $productListing->getLatestDraft();
        }

        $latestVersion = 1;
        $latestDraft = $productListing?->getLatestDraft();
        if (null !== $latestDraft) {
            $latestVersion = $latestDraft->getVersionNumber() + 1;
        }

        /** @var ProductDraftInterface $productDraft */
        $productDraft = $this->productDraftFactory->$factoryMethod(...$arguments);
        $productDraft->setCode($codeIdentifier);
        $productDraft->setVersionNumber($latestVersion);

        if (null === $productListing) {
            $productListing = $this->createProductListingForProductDraft($productDraft, $vendor);
        }

        $productDraft->setProductListing($productListing);

        return $productDraft;
    }

    private function createProductListingForProductDraft(
        ProductDraftInterface $productDraft,
        VendorInterface $vendor
    ): ProductListingInterface {
        /** @var ProductListingInterface $productListing */
        $productListing = $this->productListingFactory->createNew();

        $productListing->setCode($productDraft->getCode());
        $productListing->addProductDraft($productDraft);
        $productListing->setVendor($vendor);

        return $productListing;
    }
}
