<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product;

use BitBag\OpenMarketplace\AcceptanceOperator\ProductDraftAcceptanceOperatorInterface;
use BitBag\OpenMarketplace\Importer\Product\ProductImporter;
use BitBag\OpenMarketplace\Importer\Product\Resolver\ProductResourceResolverInterface;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductImporterSpec extends ObjectBehavior
{
    public function let(
        ProductResourceResolverInterface $productDraftResourceResolver,
        RepositoryInterface $vendorRepository,
        EntityManagerInterface $entityManager,
        ProductDraftAcceptanceOperatorInterface $productDraftService
    ) {
        $this->beConstructedWith(
            $productDraftResourceResolver,
            $vendorRepository,
            $entityManager,
            $productDraftService,
            [],
            []
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductImporter::class);
    }
}
