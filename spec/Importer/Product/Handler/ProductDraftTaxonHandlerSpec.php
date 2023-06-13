<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Importer\Product\Handler;

use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftInterface;
use BitBag\OpenMarketplace\Entity\ProductListing\ProductDraftTaxonInterface;
use BitBag\OpenMarketplace\Importer\Product\Clearer\ProductDraftRelationsClearerInterface;
use BitBag\OpenMarketplace\Importer\Product\Handler\ProductDraftTaxonHandler;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ProductDraftTaxonHandlerSpec extends ObjectBehavior
{
    public function let(
        RepositoryInterface $taxonRepository,
        FactoryInterface $productDraftTaxonFactory,
        ProductDraftRelationsClearerInterface $productDraftTaxonClearer
    ) {
        $this->beConstructedWith(
            $taxonRepository,
            $productDraftTaxonFactory,
            $productDraftTaxonClearer
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(ProductDraftTaxonHandler::class);
    }

    public function it_does_not_operate_on_invalid_data(
        ProductDraftInterface $productDraft,
        RepositoryInterface $taxonRepository,
        ProductDraftTaxonInterface $productDraftTaxon,
        ProductDraftRelationsClearerInterface $productDraftTaxonClearer,
        FactoryInterface $productDraftTaxonFactory,
        TaxonInterface $taxon
    ): void {
        $taxonRepository->findOneBy(['code' => 'test'])->shouldNotBeCalled();

        $productDraftTaxonClearer->clear($productDraft)->shouldNotBeCalled();

        $taxon->getParent()->shouldNotBeCalled();
        $productDraftTaxonFactory->createNew()->shouldNotBeCalled();
        $productDraftTaxon->setProductDraft($productDraft)->shouldNotBeCalled();
        $productDraftTaxon->setTaxon($taxon)->shouldNotBeCalled();
        $productDraft->setMainTaxon($taxon)->shouldNotBeCalled();

        $this->handle($productDraft, [], null);
    }

    public function it_handles_taxon_relation_without_generating_full_tree(
        ProductDraftInterface $productDraft,
        RepositoryInterface $taxonRepository,
        ProductDraftTaxonInterface $productDraftTaxons,
        ProductDraftTaxonInterface $productDraftTaxon,
        ProductDraftRelationsClearerInterface $productDraftTaxonClearer,
        FactoryInterface $productDraftTaxonFactory,
        TaxonInterface $taxon
    ): void {
        $taxonRepository->findOneBy(['code' => 'test'])->willReturn($taxon);
        $productDraftTaxonClearer->clear($productDraft)->shouldBeCalled();

        $taxon->getParent()->willReturn(null);

        $productDraftTaxonFactory->createNew()->willReturn($productDraftTaxon);
        $productDraftTaxon->setProductDraft($productDraft)->shouldBeCalled();
        $productDraftTaxon->setTaxon($taxon)->shouldBeCalled();
        $productDraft->addProductDraftTaxon($productDraftTaxon)->shouldBeCalled();

        $productDraft->setMainTaxon($taxon)->shouldBeCalled();

        $this->handle($productDraft, ['taxon_code' => 'test'], null);
    }

    public function it_handles_taxon_relation_with_taxon_sub_tree(
        ProductDraftInterface $productDraft,
        RepositoryInterface $taxonRepository,
        ProductDraftTaxonInterface $productDraftTaxon,
        ProductDraftRelationsClearerInterface $productDraftTaxonClearer,
        FactoryInterface $productDraftTaxonFactory,
        TaxonInterface $taxon,
        TaxonInterface $parentTaxon
    ): void {
        $taxonRepository->findOneBy(['code' => 'test'])->willReturn($taxon);

        $productDraftTaxonClearer->clear($productDraft)->shouldBeCalled();

        $taxon->getParent()->willReturn();
        $parentTaxon->getCode()->willReturn('parent-test');
        $parentTaxon->getParent()->willReturn(null);

        $productDraftTaxonFactory->createNew()->willReturn($productDraftTaxon);
        $productDraftTaxon->setProductDraft($productDraft)->shouldBeCalled();
        $productDraftTaxon->setTaxon($taxon)->shouldBeCalled();
        $productDraft->addProductDraftTaxon($productDraftTaxon)->shouldBeCalled();

        $productDraft->setMainTaxon($taxon)->shouldBeCalled();

        $this->handle($productDraft, ['taxon_code' => 'test', 'visible_in_all_taxon_levels' => 'true'], null);
    }
}
