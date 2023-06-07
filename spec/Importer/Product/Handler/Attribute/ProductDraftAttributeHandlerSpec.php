<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\DraftAttributeFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeTranslationFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeValueFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\Attribute\ProductDraftAttributeHandler;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Attribute\Model\AttributeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftAttributeHandlerSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $draftAttributeRepository,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext
    ) {
        $this->beConstructedWith(
            $draftAttributeRepository,
            $draftAttributeFactory,
            $draftAttributeTranslationFactory,
            $draftAttributeValueFactory,
            $entityManager,
            $localeContext
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftAttributeHandler::class);
    }

    public function it_does_not_operate_on_data_if_conditions_arent_met(
        ProductDraftInterface $productDraft,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        AttributeInterface $attribute,
        RepositoryInterface $draftAttributeRepository,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        VendorInterface $vendor
    ): void {
        $draftAttributeValueFactory
            ->createWithData($attribute, $productDraft, 'locale', 'value')
            ->shouldNotBeCalled();

        $localeContext->getLocaleCode()->shouldNotBeCalled();
        $draftAttributeRepository->findOneBy([])->shouldNotBeCalled();
        $draftAttributeFactory->createTyped('type', $vendor)->shouldNotBeCalled();
        $draftAttributeTranslationFactory->createWithData('code', 'locale', $attribute)->shouldNotBeCalled();
        $entityManager->persist($attribute)->shouldNotBeCalled();

        $productDraft->addAttribute($attribute)->shouldNotBeCalled();

        $this->handle($productDraft, [], $vendor);
    }

    public function it_adds_attributes_to_product_draft(
        ProductDraftInterface $productDraft,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        AttributeInterface $attribute,
        RepositoryInterface $draftAttributeRepository,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        VendorInterface $vendor
    ): void {
        $row = [
            'type' => 'text',
            'code' => 'attribute_code',
            'value' => 'attribute_value',
        ];

        $draftAttributeValueFactory
            ->createWithData($attribute, $productDraft, 'locale', 'value')
            ->shouldNotBeCalled();

        $localeContext->getLocaleCode()->shouldNotBeCalled();
        $draftAttributeRepository->findOneBy([])->shouldNotBeCalled();
        $draftAttributeTranslationFactory->createWithData('code', 'locale', $attribute)->shouldNotBeCalled();
        $entityManager->persist($attribute)->shouldNotBeCalled();

        $productDraft->addAttribute($attribute)->shouldNotBeCalled();

        $this->handle($productDraft, $row, $vendor);
    }
}
