<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftShippingHandler;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Repository\ShippingCategoryRepositoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Model\ShippingCategoryInterface;

final class ProductDraftShippingHandlerSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $shippingCategoryRepository,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith(
            $shippingCategoryRepository,
            $logger
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftShippingHandler::class);
    }

    public function it_does_not_operate_on_invalid_data(
        ProductDraftInterface $productDraft,
        ShippingCategoryRepositoryInterface $shippingCategoryRepository
    ): void {
        $shippingCategoryRepository->findOneBy(['code' => 'test'])->shouldNotBeCalled();

        $productDraft->setShippingRequired(true)->shouldNotBeCalled();
        $productDraft->setShippingCategory(null)->shouldNotBeCalled();

        $this->handle($productDraft, [], null);
    }

    public function it_assigns_shipping_data_to_product_draft(
        ProductDraftInterface $productDraft,
        ShippingCategoryRepositoryInterface $shippingCategoryRepository,
        ShippingCategoryInterface $shippingCategory
    ): void {
        $shippingCategoryRepository->findOneBy(['code' => 'default'])->willReturn($shippingCategory);

        $productDraft->setShippingRequired(true)->shouldBeCalled();
        $productDraft->setShippingCategory($shippingCategory)->shouldBeCalled();

        $this->handle($productDraft, ['shipping_category' => 'default'], null);
    }
}
