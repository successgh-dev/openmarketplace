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
use BitBag\OpenMarketplace\Entity\ProductListing\ProductTranslationInterface;
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

    public function it_does_not_operate_on_invalid_data(
        ProductDraftInterface $productDraft,
        ProductTranslationInterface $productDraftTranslation,
        FactoryInterface $productTranslationFactory,
        ProductTranslationRepositoryInterface $productDraftTranslationRepository,
        LocaleContextInterface $localeContext,
        SlugGeneratorInterface $slugGenerator
    ): void {
        $slugGenerator->generate('test')->shouldNotBeCalled();
        $localeContext->getLocaleCode()->shouldNotBeCalled();

        $productDraftTranslationRepository->findOneBy([])->shouldNotBeCalled();
        $productTranslationFactory->createNew()->shouldNotBeCalled();
        $productDraftTranslation->setProductDraft($productDraft)->shouldNotBeCalled();
        $productDraft->addTranslation($productDraftTranslation)->shouldNotBeCalled();

        $this->handle($productDraft, [], null);
    }

    public function it_assigns_creates_new_product_draft_translation(
        ProductDraftInterface $productDraft,
        ProductTranslationInterface $productDraftTranslation,
        FactoryInterface $productTranslationFactory,
        ProductTranslationRepositoryInterface $productDraftTranslationRepository,
        LocaleContextInterface $localeContext,
        SlugGeneratorInterface $slugGenerator
    ): void {
        $slugGenerator->generate('url-test')->willReturn('url-test');
        $localeContext->getLocaleCode()->willReturn('en');

        $productDraftTranslationRepository->findOneBy([
            'productDraft' => $productDraft,
            'locale' => 'en',
        ])->willReturn(null);

        $productDraftTranslation->getId()->willReturn(null);

        $productTranslationFactory->createNew()->willReturn($productDraftTranslation);

        $productDraftTranslation->setName('product-test')->shouldBeCalled();
        $productDraftTranslation->setDescription('test description')->shouldBeCalled();
        $productDraftTranslation->setSlug('url-test')->shouldBeCalled();
        $productDraftTranslation->setLocale('en')->shouldBeCalled();
        $productDraftTranslation->setShortDescription(null)->shouldBeCalled();
        $productDraftTranslation->setMetaDescription(null)->shouldBeCalled();
        $productDraftTranslation->setMetaKeywords(null)->shouldBeCalled();

        $productDraftTranslation->setProductDraft($productDraft)->shouldBeCalled();
        $productDraft->addTranslation($productDraftTranslation)->shouldBeCalled();

        $this->handle($productDraft, [
            'name' => 'product-test',
            'description' => 'test description',
            'url' => 'url-test',
        ], null);
    }

    public function it_updates_existing_product_draft_translation(
        ProductDraftInterface $productDraft,
        ProductTranslationInterface $productDraftTranslation,
        FactoryInterface $productTranslationFactory,
        ProductTranslationRepositoryInterface $productDraftTranslationRepository,
        LocaleContextInterface $localeContext,
        SlugGeneratorInterface $slugGenerator
    ): void {
        $slugGenerator->generate('url-test')->willReturn('url-test');
        $localeContext->getLocaleCode()->willReturn('en');

        $productDraftTranslationRepository->findOneBy([
            'productDraft' => $productDraft,
            'locale' => 'en',
        ])->willReturn($productDraftTranslation);

        $productDraftTranslation->getId()->willReturn(1);

        $productTranslationFactory->createNew()->shouldNotBeCalled();

        $productDraftTranslation->setName('product-test')->shouldBeCalled();
        $productDraftTranslation->setDescription('test description')->shouldBeCalled();
        $productDraftTranslation->setSlug('url-test')->shouldBeCalled();
        $productDraftTranslation->setLocale('en')->shouldBeCalled();
        $productDraftTranslation->setShortDescription(null)->shouldBeCalled();
        $productDraftTranslation->setMetaDescription(null)->shouldBeCalled();
        $productDraftTranslation->setMetaKeywords(null)->shouldBeCalled();

        $productDraftTranslation->setProductDraft($productDraft)->shouldNotBeCalled();
        $productDraft->addTranslation($productDraftTranslation)->shouldNotBeCalled();

        $this->handle($productDraft, [
            'name' => 'product-test',
            'description' => 'test description',
            'url' => 'url-test',
        ], null);
    }
}
