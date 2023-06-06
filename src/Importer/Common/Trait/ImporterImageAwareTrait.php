<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Common\Trait;

trait ImporterImageAwareTrait
{
    private function ensureDirectoryExists(string $path): void
    {
        if (!file_exists($path) || !is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function imageExists(string $filePath, string $imageUrl): void
    {
        try {
            if (!file_exists($filePath)) {
                $fileContents = file_get_contents($imageUrl);
                file_put_contents($filePath, $fileContents);
            }
        } catch (\Exception $exception) {
            $this->logger->warning(\sprintf('Image url %s is invalid', $imageUrl));
        }
    }
}
