<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Repository\ProductListing;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductTranslationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface ProductTranslationRepositoryInterface extends RepositoryInterface
{
    public function save(ProductTranslationInterface $productTranslation): void;

    public function saveCollection(array $productTranslations): void;
}
