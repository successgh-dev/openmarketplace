<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\DraftAttributeValueInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\DraftAttributeFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
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
        LocaleContextInterface $localeContext,
        ProductDraftRelationsClearerInterface $productDraftAttributeClearer
    ) {
        $this->beConstructedWith(
            $draftAttributeRepository,
            $draftAttributeFactory,
            $draftAttributeTranslationFactory,
            $draftAttributeValueFactory,
            $entityManager,
            $localeContext,
            $productDraftAttributeClearer
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
        VendorInterface $vendor,
        ProductDraftRelationsClearerInterface $productDraftAttributeClearer
    ): void {
        $draftAttributeValueFactory
            ->createWithData($attribute, $productDraft, 'locale', 'value')
            ->shouldNotBeCalled();

        $localeContext->getLocaleCode()->shouldNotBeCalled();
        $draftAttributeRepository->findOneBy([])->shouldNotBeCalled();
        $draftAttributeFactory->createTyped('type', $vendor)->shouldNotBeCalled();
        $draftAttributeTranslationFactory->createWithData('code', 'locale', $attribute)->shouldNotBeCalled();
        $entityManager->persist($attribute)->shouldNotBeCalled();

        $productDraftAttributeClearer->clear($productDraft)->shouldNotBeCalled();

        $productDraft->addAttribute($attribute)->shouldNotBeCalled();

        $this->handle($productDraft, [], $vendor);
    }

    public function it_adds_new_attributes_to_product_draft(
        ProductDraftInterface $productDraft,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        RepositoryInterface $draftAttributeRepository,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        VendorInterface $vendor,
        ProductDraftRelationsClearerInterface $productDraftAttributeClearer,
        DraftAttributeInterface $draftAttribute,
        DraftAttributeValueInterface $draftAttributeValue
    ): void {
        $row = [
            'type' => 'text',
            'code' => 'attribute_code',
            'value' => 'attribute_value',
        ];

        $productDraftAttributeClearer->clear($productDraft)->shouldBeCalled();

        $draftAttributeRepository->findOneBy(['code' => 'attribute_code'])->willReturn(null);

        $draftAttributeFactory->createTyped('text', $vendor)->willReturn($draftAttribute);
        $draftAttribute->setCode('attribute_code')->shouldBeCalled();

        $localeContext->getLocaleCode()->willReturn('en');

        $draftAttributeTranslationFactory->createWithData(
            'attribute_code',
            'en',
            $draftAttribute
        )->shouldBeCalled();

        $entityManager->persist($draftAttribute);

        $draftAttributeValueFactory
            ->createWithData($draftAttribute, $productDraft, 'en', 'attribute_value')
            ->willReturn($draftAttributeValue);

        $productDraft->addAttribute($draftAttributeValue)->shouldBeCalled();

        $this->handle($productDraft, ['attributes' => json_encode([$row])], $vendor);
    }

    public function it_adds_existing_attributes_to_product_draft(
        ProductDraftInterface $productDraft,
        DraftAttributeValueFactoryInterface $draftAttributeValueFactory,
        RepositoryInterface $draftAttributeRepository,
        EntityManagerInterface $entityManager,
        LocaleContextInterface $localeContext,
        DraftAttributeFactoryInterface $draftAttributeFactory,
        DraftAttributeTranslationFactoryInterface $draftAttributeTranslationFactory,
        VendorInterface $vendor,
        ProductDraftRelationsClearerInterface $productDraftAttributeClearer,
        DraftAttributeInterface $draftAttribute,
        DraftAttributeValueInterface $draftAttributeValue
    ): void {
        $row = [
            'type' => 'text',
            'code' => 'attribute_code',
            'value' => 'attribute_value',
        ];

        $productDraftAttributeClearer->clear($productDraft)->shouldBeCalled();

        $localeContext->getLocaleCode()->willReturn('en');

        $draftAttributeRepository->findOneBy(['code' => 'attribute_code'])->willReturn($draftAttribute);

        $draftAttributeFactory->createTyped('text', $vendor)->shouldNotBeCalled();
        $draftAttribute->setCode('attribute_code')->shouldNotBeCalled();

        $draftAttributeTranslationFactory->createWithData(
            'attribute_code',
            'en',
            $draftAttribute
        )->shouldNotBeCalled();

        $entityManager->persist($draftAttribute)->shouldNotBeCalled();

        $draftAttributeValueFactory
            ->createWithData($draftAttribute, $productDraft, 'en', 'attribute_value')
            ->willReturn($draftAttributeValue);

        $productDraft->addAttribute($draftAttributeValue)->shouldBeCalled();

        $this->handle($productDraft, ['attributes' => json_encode([$row])], $vendor);
    }
}
