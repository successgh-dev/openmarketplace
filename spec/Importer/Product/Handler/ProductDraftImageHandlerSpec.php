<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Factory\ProductDraftImageFactoryInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;

final class ProductDraftImageHandlerSpec extends ObjectBehavior
{
    public function let(
        ProductDraftImageFactoryInterface $draftImageFactory,
        ImageUploaderInterface $imageUploader,
        LoggerInterface $logger,
        ProductDraftRelationsClearerInterface $productDraftImageClearer,
        EntityManagerInterface $entityManager
    ): void {
        $this->beConstructedWith(
            $draftImageFactory,
            $imageUploader,
            $logger,
            $productDraftImageClearer,
            $entityManager,
            '',
            ''
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftImageHandler::class);
    }
}
