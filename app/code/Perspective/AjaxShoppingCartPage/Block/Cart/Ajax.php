<?php

namespace Perspective\AjaxShoppingCartPage\Block\Cart;

use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Cart;

class Ajax extends Template
{
    protected $cart;

    public function __construct(
        Template\Context $context,
        Cart $cart,
        array $data = []
    ) {
        $this->cart = $cart;
        parent::__construct($context, $data);
    }

    public function getCartItemsVariations()
    {
        $items = $this->cart->getQuote()->getAllVisibleItems();
        $cartItemsVariations = [];

        foreach ($items as $item) {
            $itemId = $item->getId();
            $product = $item->getProduct();
            $isConfigurable = $product->getData('type_id') === 'configurable';

            if (!$isConfigurable) {
                continue;
            }

            $productId = $product->getData('entity_id');
            $productSku = $product->getData('sku');
            $productName = $product->getData('product_name');
            $typeInstance = $product->getTypeInstance();
            $productVariations = $typeInstance->getConfigurableAttributesAsArray($product);
            $cartItemsVariations[$itemId] = $productVariations;
        }

        return $cartItemsVariations;
    }
}
