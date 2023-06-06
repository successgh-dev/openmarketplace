<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftTranslationHandler;
use BitBag\OpenMarketplace\Repository\ProductListing\ProductTranslationRepositoryInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Product\Generator\SlugGeneratorInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductDraftTranslationHandlerSpec extends ObjectBehavior
{
    public function let(
        FactoryInterface $productTranslationFactory,
        ProductTranslationRepositoryInterface $productDraftTranslationRepository,
        LocaleContextInterface $localeContext,
        SlugGeneratorInterface $slugGenerator
    ) {
        $this->beConstructedWith(
            $productTranslationFactory,
            $productDraftTranslationRepository,
            $localeContext,
            $slugGenerator
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftTranslationHandler::class);
    }
}
