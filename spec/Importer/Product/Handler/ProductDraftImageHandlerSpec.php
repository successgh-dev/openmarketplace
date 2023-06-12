<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftImage;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Factory\ProductDraftImageFactoryInterface;
use BitBag\OpenMarketplace\Importer\Common\Helper\ImporterImageFileHelperInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftImageHandler;
use Doctrine\ORM\EntityManagerInterface;
use PhpSpec\ObjectBehavior;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ProductDraftImageHandlerSpec extends ObjectBehavior
{
    public function let(
        ProductDraftImageFactoryInterface $draftImageFactory,
        ImageUploaderInterface $imageUploader,
        LoggerInterface $logger,
        ProductDraftRelationsClearerInterface $productDraftImageClearer,
        ImporterImageFileHelperInterface $importerImageFileHelper,
        EntityManagerInterface $entityManager
    ): void {
        $this->beConstructedWith(
            $draftImageFactory,
            $imageUploader,
            $logger,
            $productDraftImageClearer,
            $entityManager,
            $importerImageFileHelper,
            '',
            'spec/Importer/Product/testfiles'
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftImageHandler::class);
    }

    public function it_does_not_import_invalid_data(
        ProductDraftInterface $productDraft,
        ProductDraftRelationsClearerInterface $productDraftImageClearer,
        ProductDraftImageFactoryInterface $draftImageFactory,
        EntityManagerInterface $entityManager,
        ImageUploaderInterface $imageUploader,
        ProductDraftImage $productDraftImage,
        LoggerInterface $logger
    ): void {
        $productDraftImageClearer->clear($productDraft)->shouldNotBeCalled();
        $draftImageFactory->createNew()->shouldNotBeCalled();
        $imageUploader->upload($productDraftImage)->shouldNotBeCalled();
        $entityManager->persist($productDraftImage)->shouldNotBeCalled();
        $logger->warning('message')->shouldNotBeCalled();

        $this->handle($productDraft, [], null);
    }

//    TODO improve file related testing
//    public function it_imports_new_image_data_correctly(
//        ProductDraftInterface $productDraft,
//        ProductDraftRelationsClearerInterface $productDraftImageClearer,
//        ProductDraftImageFactoryInterface $draftImageFactory,
//        EntityManagerInterface $entityManager,
//        ImporterImageFileHelperInterface $importerImageFileHelper,
//        ImageUploaderInterface $imageUploader,
//        ProductDraftImage $productDraftImage
//    ): void {
//        $uploadedFile = new UploadedFile('spec/Importer/Product/testfiles/image.png', 'image.png');
//
//        $importerImageFileHelper->ensureDirectoryExists('')->shouldBeCalled();
//
//        $productDraftImageClearer->clear($productDraft)->shouldBeCalled();
//
//        $importerImageFileHelper->processImage(
//            __FILE__,
//            'spec/Importer/Product/testfiles/image.png'
//
//        )->willReturn($uploadedFile);
//
//        $draftImageFactory->createNew()->willReturn($productDraftImage);
//        $productDraftImage->setFile($uploadedFile)->shouldBeCalled();
//        $productDraftImage->setType('main')->shouldBeCalled();
//
//        $imageUploader->upload($productDraftImage)->shouldBeCalled();
//        $productDraft->addImage($productDraftImage)->shouldBeCalled();
//        $productDraftImage->setOwner($productDraft)->shouldBeCalled();
//
//        $entityManager->persist($productDraftImage)->shouldBeCalled();
//        $entityManager->persist($productDraft)->shouldBeCalled();
//
//        $this->handle($productDraft, ['images' => '[{"type": "main", "url": "spec/Importer/Product/testfiles/image.png"}]'], null);
//    }
}
