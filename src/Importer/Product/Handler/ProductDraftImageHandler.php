<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\OpenMarketplace\Factory\ProductDraftImageFactoryInterface;
use BitBag\OpenMarketplace\Importer\Common\Exception\ImporterInvalidImagePathException;
use BitBag\OpenMarketplace\Importer\Common\Helper\ImporterImageFileHelperInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Webmozart\Assert\Assert;

final class ProductDraftImageHandler extends AbstractHandler
{
    protected static string $dataKey = 'images';

    public function __construct(
        private ProductDraftImageFactoryInterface $draftImageFactory,
        private ImageUploaderInterface $imageUploader,
        private LoggerInterface $logger,
        private ProductDraftRelationsClearerInterface $productDraftImageClearer,
        private EntityManagerInterface $entityManager,
        private ImporterImageFileHelperInterface $importerImageFileHelper,
        private string $projectRootDir = '',
        private string $tempMediaPath = ''
    ) {
    }

    public function handle(
        ProductDraftInterface $productDraft,
        array $row,
        ?VendorInterface $vendor
    ): void {
        if (false === $this->supports(self::$dataKey, $row)) {
            return;
        }

        Assert::notEq(
            $this->tempMediaPath,
            '',
            'Please set a environment (TEMP_MEDIA_PATH) media path for importer image downloads '
        );

        $mediaPath = \implode(\DIRECTORY_SEPARATOR, [$this->projectRootDir, $this->tempMediaPath]);
        $imagesString = $row[self::$dataKey];

        if (null === $imagesString) {
            return;
        }

        $imagesArray = json_decode($imagesString, true);

        if (null === $imagesArray || [] === $imagesArray) {
            return;
        }

        try {
            $this->importerImageFileHelper->ensureDirectoryExists($mediaPath);
            $this->productDraftImageClearer->clear($productDraft);
            foreach ($imagesArray as $image) {
                $imageUrl = $image['url'];
                $imageType = $image['type'];

                $imageUrlArray = explode('/', $imageUrl);
                $filename = end($imageUrlArray);

                $filePath = \implode(\DIRECTORY_SEPARATOR, [$mediaPath, $filename]);

                $uploadedImage = $this->importerImageFileHelper->processImage($filePath, $imageUrl);

                if (null === $uploadedImage) {
                    throw new ImporterInvalidImagePathException(
                        \sprintf(
                            'Couldn\'t save image from url %s to following path: %s',
                            $imageUrl,
                            $filePath
                        )
                    );
                }

                $productImage = $this->draftImageFactory->createNew();
                $productImage->setFile($uploadedImage);
                $productImage->setType($imageType);

                $this->imageUploader->upload($productImage);

                $productDraft->addImage($productImage);
                $productImage->setOwner($productDraft);

                $this->entityManager->persist($productImage);
                $this->entityManager->persist($productDraft);
            }
        } catch (\Exception $exception) {
            $this->logger->warning(\sprintf(
                'Couldn\'t retrieve image for taxon code %s, and image json %s with exception %s',
                $productDraft->getCode(),
                $imagesString,
                $exception->getMessage()
            ));
        }
    }
}
