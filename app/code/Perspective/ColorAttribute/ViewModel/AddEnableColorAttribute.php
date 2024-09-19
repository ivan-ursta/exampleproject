<?php

namespace Perspective\ColorAttribute\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Catalog\Model\Layer\Resolver;



class AddEnableColorAttribute implements ArgumentInterface
{

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @var ProductViewBlock
     */
    private $productViewBlock;

    public function __construct(
        ProductViewBlock $productViewBlock,
        Resolver $layerResolver
    )
    {
        $this->productViewBlock = $productViewBlock;
        $this->layerResolver = $layerResolver;
    }

    public function getCurrentProduct()
    {
        return $this->productViewBlock->getProduct();
    }
    public function getCurrentCategory()
    {
        $layer = $this->layerResolver->get();
        return $layer->getCurrentCategory();
    }
}
