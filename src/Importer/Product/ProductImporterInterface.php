<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\OpenMarketplace\Importer\Product;

use BitBag\SyliusCmsPlugin\Importer\ImporterInterface;

interface ProductImporterInterface extends ImporterInterface
{
    public const CODE_COLUMN = 'product_code';

    public const VENDOR_COLUMN = 'vendor_uuid';

    public const AUTO_VERIFY = 'auto_verify';

    public const NAME_COLUMN = 'name';

    public const ATTRIBUTES_COLUMN = 'attributes';

    public const SLUG_COLUMN = 'url';

    public const IMAGES_COLUMN = 'images';

    public const PRICE_COLUMN = 'price';

    public const DESCRIPTION_COLUMN = 'description';

    public const ENABLED_COLUMN = 'enabled';
}
