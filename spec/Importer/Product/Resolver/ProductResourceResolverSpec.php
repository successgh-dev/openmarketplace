<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Resolver;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\ProductListingFromDraftFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Resolver\ProductResourceResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductResourceResolverSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $repository,
        FactoryInterface $factory,
        ProductListingFromDraftFactoryInterface $productListingFromDraftFactory
    ) {
        $this->beConstructedWith(
            $repository,
            $factory,
            $productListingFromDraftFactory
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductResourceResolver::class);
    }

    public function it_returns_a_resource_if_found(RepositoryInterface $repository, ResourceInterface $resource): void
    {
        $repository->findOneBy(['code' => 'example'])->willReturn($resource);

        $this->getResource('example')->shouldBeEqualTo($resource);
    }

    public function it_creates_a_new_product_draft(
        RepositoryInterface $repository,
        FactoryInterface $factory,
        ResourceInterface $resource
    ): void {
        $repository->findOneBy(['code' => 'example'])->willReturn(null);
        $factory->createNew()->willReturn($resource);

        $this->getResource('example')->shouldBeEqualTo($resource);
    }

    public function it_returns_product_draft_by_code_and_vendor(
        RepositoryInterface $repository,
        ProductListingInterface $resource,
        ProductDraftInterface $productDraft,
        VendorInterface $vendor
    ): void {
        $repository->findOneBy(['code' => 'example', 'vendor' => $vendor])->willReturn($resource);

        $resource->getLatestDraft()->willReturn($productDraft);
        $productDraft->getStatus()->willReturn(ProductDraftInterface::STATUS_CREATED);
        $this->getResourceByCodeAndVendor('example', $vendor)->shouldBeEqualTo($productDraft);
    }

    public function it_returns_new_product_draft_by_code_and_vendor_if_status_is_on_disallowed_list(
        RepositoryInterface $repository,
        ProductListingInterface $resource,
        ProductDraftInterface $verifiedProductDraft,
        ProductDraftInterface $newProductDraft,
        VendorInterface $vendor,
        FactoryInterface $factory,
        ProductListingFromDraftFactoryInterface $productListingFromDraftFactory
    ): void {
        $repository->findOneBy(['code' => 'example', 'vendor' => $vendor])->willReturn($resource);

        $resource->getLatestDraft()->willReturn($verifiedProductDraft);
        $verifiedProductDraft->getStatus()->willReturn(ProductDraftInterface::STATUS_VERIFIED);
        $verifiedProductDraft->getVersionNumber()->willReturn(1);

        $factory->createNew()->shouldBeCalled()->willReturn($newProductDraft);
        $newProductDraft->setCode('example')->shouldBeCalled();
        $newProductDraft->setVersionNumber(2)->shouldBeCalled();

        $productListingFromDraftFactory->createNew($newProductDraft, $vendor)->shouldBeCalled();

        $this->getResourceByCodeAndVendor('example', $vendor);
    }
}
