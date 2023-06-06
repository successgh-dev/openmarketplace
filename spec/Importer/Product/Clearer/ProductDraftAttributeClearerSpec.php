<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Clearer;

use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftAttributeClearer;
use PhpSpec\ObjectBehavior;

final class ProductDraftAttributeClearerSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftAttributeClearer::class);
    }
}
