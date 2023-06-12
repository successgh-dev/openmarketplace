<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Common\Helper;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImporterImageFileHelperInterface
{
    public function ensureDirectoryExists(string $path): void;

    public function processImage(string $filePath, string $imageUrl): ?UploadedFile;
}
