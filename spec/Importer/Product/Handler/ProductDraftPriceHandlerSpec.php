<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Importer\Product\Factory\ProductListingPriceFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftPriceHandler;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;

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
}
