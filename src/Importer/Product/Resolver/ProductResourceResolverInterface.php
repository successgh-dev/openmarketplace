<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product\Resolver;

use BitBag\OpenMarketplace\Entity\VendorInterface;
use BitBag\SyliusCmsPlugin\Resolver\ResourceResolverInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ProductResourceResolverInterface extends ResourceResolverInterface
{
    public function getResourceByCodeAndVendor(
        string $codeIdentifier,
        VendorInterface $vendor,
        string $factoryMethod = 'createNew',
        array $arguments = [],
        ): ResourceInterface;
}
