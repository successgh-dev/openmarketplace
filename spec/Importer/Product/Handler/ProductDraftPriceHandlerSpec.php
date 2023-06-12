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
use BitBag\OpenMarketplace\Entity\ProductListing\ProductListingPriceInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\ProductListingPriceFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftPriceHandler;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;

final class ProductDraftPriceHandlerSpec extends ObjectBehavior
{
    public function let(
        ChannelRepositoryInterface $channelRepository,
        ProductListingPriceFactoryInterface $productListingPriceFactory,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith(
            $channelRepository,
            $productListingPriceFactory,
            $logger
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftPriceHandler::class);
    }

    public function it_does_not_import_price_data_for_invalid_input(
        ProductDraftInterface $productDraft,
        ChannelRepositoryInterface $channelRepository,
        ProductListingPriceFactoryInterface $productListingPriceFactory,
        ProductListingPriceInterface $productListingPrice,
        LoggerInterface $logger
    ): void {
        $channelRepository->findOneByCode('test')->shouldNotBeCalled();
        $productListingPriceFactory->createWithData(
            'test',
            $productDraft,
            100,
            100,
            100
        )->shouldNotBeCalled();

        $productDraft->addProductListingPrice($productListingPrice)->shouldNotBeCalled();

        $logger->warning('No pricing data found in input file')->shouldBeCalled();

        $this->handle($productDraft, [], null);
    }

    public function it_imports_product_draft_pricing_data(
        ProductDraftInterface $productDraft,
        ChannelRepositoryInterface $channelRepository,
        ProductListingPriceFactoryInterface $productListingPriceFactory,
        ProductListingPriceInterface $productListingPrice,
        LoggerInterface $logger,
        ChannelInterface $channel
    ): void {
        $channelRepository->findOneByCode('test')->willReturn($channel);
        $productListingPriceFactory->createWithData(
            'test',
            $productDraft,
            456,
            123,
            345
        )->willReturn($productListingPrice);

        $productDraft->addProductListingPrice($productListingPrice)->shouldBeCalled();

        $logger->warning('No pricing data found in input file')->shouldNotBeCalled();

        $this->handle($productDraft, [
            'prices' => '{"test":{"minimum_price":123,"original_price":345,"price":456}}',
        ], null);
    }
}
