<?php

namespace Perspective\ClothingMaterial\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;

class RelatedModel implements ArgumentInterface
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $_registry;
    public function __construct(
        \Magento\Framework\Registry $registry
    )
    {
        $this->_registry = $registry;
    }
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }
}
