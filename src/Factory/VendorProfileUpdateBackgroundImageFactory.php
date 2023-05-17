<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Factory;

use BitBag\OpenMarketplace\Entity\VendorBackgroundImageInterface;
use BitBag\OpenMarketplace\Entity\VendorProfileInterface;
use BitBag\OpenMarketplace\Entity\VendorProfileUpdateBackgroundImage;

final class VendorProfileUpdateBackgroundImageFactory implements VendorProfileUpdateBackgroundImageFactoryInterface
{
    public function createNew(): VendorBackgroundImageInterface
    {
        return new VendorProfileUpdateBackgroundImage();
    }

    public function createWithFileAndOwner(VendorBackgroundImageInterface $uploadedBackgroundImage, VendorProfileInterface $vendorProfile): VendorBackgroundImageInterface
    {
        $backgroundImage = $this->createNew();
        $backgroundImage->setFile($uploadedBackgroundImage->getFile());
        $backgroundImage->setOwner($vendorProfile);

        return $backgroundImage;
    }
}
