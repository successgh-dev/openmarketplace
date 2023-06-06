<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler\Attribute;

use BitBag\OpenMarketplace\Factory\DraftAttributeFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeTranslationFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Factory\DraftAttributeValueFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\Attribute\ProductDraftAttributeHandler;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
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
}
