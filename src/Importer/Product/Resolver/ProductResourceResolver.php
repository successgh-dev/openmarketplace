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
use BitBag\OpenMarketplace\Factory\ProductListingFromDraftFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductResourceResolver implements ProductResourceResolverInterface
{
    public function __construct(
        private RepositoryInterface $repository,
        private FactoryInterface $factory,
        private ProductListingFromDraftFactoryInterface $productListingFromDraftFactory
    ) {
    }

    public function getResource(string $identifier, string $factoryMethod = 'createNew'): ResourceInterface
    {
        /** @var ?ResourceInterface $resource */
        $resource = $this->repository->findOneBy([
            'code' => $identifier,
        ]);

        if (null !== $resource) {
            return $resource;
        }

        return $this->factory->$factoryMethod();
    }

    public function getResourceByCodeAndVendor(
        string $codeIdentifier,
        VendorInterface $vendor,
        string $factoryMethod = 'createNew',
        array $arguments = [],
        ): ResourceInterface {
        /** @var ProductListingInterface|null $resource */
        $resource = $this->repository->findOneBy([
            'code' => $codeIdentifier,
            'vendor' => $vendor,
        ]);

        $disallowedStatuses = [
            ProductDraftInterface::STATUS_UNDER_VERIFICATION,
            ProductDraftInterface::STATUS_VERIFIED,
        ];

        //TODO Marcin Kukliński will refactor this
        if (null !== $resource &&
            null !== $resource->getLatestDraft() &&
            false === in_array($resource->getLatestDraft()->getStatus(), $disallowedStatuses)
        ) {
            return $resource->getLatestDraft();
        }

        $latestVersion = 1;
        $latestDraft = $resource?->getLatestDraft();
        if (null !== $latestDraft) {
            $latestVersion = $latestDraft->getVersionNumber() + 1;
        }

        /** @var ProductDraftInterface $productDraft */
        $productDraft = $this->factory->$factoryMethod(...$arguments);
        $productDraft->setCode($codeIdentifier);
        $productDraft->setVersionNumber($latestVersion);

        return $this->productListingFromDraftFactory->createNew($productDraft, $vendor);
    }
}
