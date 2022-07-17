<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Repository\ProductListing;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductListing\ProductDraftInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\ProductListing\ProductListingInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class ProductDraftRepository extends EntityRepository implements ProductDraftRepositoryInterface
{
    public function save(ProductDraftInterface $productDraft): void
    {
        $this->_em->persist($productDraft);
        $this->_em->flush();
    }

    public function findProductListingLatestProductDraft(ProductListingInterface $productListing): ?ProductDraftInterface
    {
        return $this->createQueryBuilder('pd')
            ->andWhere('pd.productListing = :productListing')
            ->setParameter('productListing', $productListing)
            ->orderBy('pd.id', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult()
            ;
    }
}