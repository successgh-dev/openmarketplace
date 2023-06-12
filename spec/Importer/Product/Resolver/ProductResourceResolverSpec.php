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
use BitBag\OpenMarketplace\Importer\Product\Resolver\ProductResourceResolver;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductResourceResolverSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $productListingRepository,
        FactoryInterface $productDraftFactory,
        FactoryInterface $productListingFactory
    ) {
        $this->beConstructedWith(
            $productListingRepository,
            $productDraftFactory,
            $productListingFactory
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductResourceResolver::class);
    }

    public function it_returns_product_listing_if_found(
        RepositoryInterface $productListingRepository,
        ProductListingInterface $productListing
    ): void {
        $productListingRepository->findOneBy(['code' => 'example'])->willReturn($productListing);

        $this->getResource('example')->shouldBeEqualTo($productListing);
    }

    public function it_creates_a_new_product_listing(
        RepositoryInterface $productListingRepository,
        ProductListingInterface $productListing,
        FactoryInterface $productListingFactory
    ): void {
        $productListingRepository->findOneBy(['code' => 'example'])->willReturn(null);
        $productListingFactory->createNew()->willReturn($productListing);

        $this->getResource('example')->shouldBeEqualTo($productListing);
    }

    public function it_returns_product_draft_by_code_and_vendor(
        RepositoryInterface $productListingRepository,
        ProductListingInterface $productListing,
        ProductDraftInterface $productDraft,
        VendorInterface $vendor
    ): void {
        $productListingRepository->findOneBy(['code' => 'example', 'vendor' => $vendor])->willReturn($productListing);

        $productListing->getLatestDraft()->willReturn($productDraft);
        $productDraft->getStatus()->willReturn(ProductDraftInterface::STATUS_CREATED);
        $this->getResourceByCodeAndVendor('example', $vendor)->shouldBeEqualTo($productDraft);
    }

    public function it_returns_new_product_draft_by_code_and_vendor_if_product_draft_status_is_on_disallowed_list(
        RepositoryInterface $productListingRepository,
        ProductListingInterface $productListing,
        ProductDraftInterface $verifiedProductDraft,
        ProductDraftInterface $newProductDraft,
        VendorInterface $vendor,
        FactoryInterface $productDraftFactory,
        ): void {
        $productListingRepository->findOneBy(['code' => 'example', 'vendor' => $vendor])->willReturn($productListing);

        $productListing->getLatestDraft()->willReturn($verifiedProductDraft);
        $verifiedProductDraft->getStatus()->willReturn(ProductDraftInterface::STATUS_VERIFIED);
        $verifiedProductDraft->getVersionNumber()->willReturn(1);

        $productDraftFactory->createNew()->shouldBeCalled()->willReturn($newProductDraft);
        $newProductDraft->setCode('example')->shouldBeCalled();
        $newProductDraft->getCode()->willReturn('code');
        $newProductDraft->setVersionNumber(2)->shouldBeCalled();
        $newProductDraft->setProductListing($productListing);

        $newProductDraft->setProductListing($productListing);

        $this->getResourceByCodeAndVendor('example', $vendor);
    }

    public function it_creates_new_product_listing_for_new_product_draft(
        RepositoryInterface $productListingRepository,
        ProductListingInterface $productListing,
        ProductDraftInterface $productDraft,
        VendorInterface $vendor,
        FactoryInterface $productDraftFactory,
        FactoryInterface $productListingFactory
    ) {
        $productListingRepository->findOneBy([
            'code' => 'code',
            'vendor' => $vendor,
        ])->willReturn(null);

        $productDraftFactory->createNew()->willReturn($productDraft);
        $productDraft->setCode('code')->shouldBeCalled();
        $productDraft->setVersionNumber(1)->shouldBeCalled();

        $productListingFactory->createNew()->willReturn($productListing);
        $productDraft->getCode()->willReturn('code');
        $productListing->setCode('code')->shouldBeCalled();
        $productListing->addProductDraft($productDraft)->shouldBeCalled();
        $productListing->setVendor($vendor)->shouldBeCalled();

        $productDraft->setProductListing($productListing)->shouldBeCalled();
        $this->getResourceByCodeAndVendor('code', $vendor);
    }
}
