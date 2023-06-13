<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Common\Helper;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ImporterImageFileHelper implements ImporterImageFileHelperInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public function ensureDirectoryExists(string $path): void
    {
        if (!file_exists($path) || !is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function getFilenameFromPath(string $path): string
    {
        $imageUrlArray = explode('/', $path);

        return end($imageUrlArray);
    }

    public function processImage(string $filePath, string $imageUrl): ?UploadedFile
    {
        try {
            if (!file_exists($filePath)) {
                $fileContents = file_get_contents($imageUrl);
                file_put_contents($filePath, $fileContents);
            }

            $filename = $this->getFilenameFromPath($filePath);

            return new UploadedFile($filePath, $filename);
        } catch (\Exception $exception) {
            $this->logger->warning(\sprintf('Image url %s is invalid', $imageUrl));
        }

        return null;
    }
}
