<?php

namespace Perspective\Holidays\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Perspective\ShippingConditions\Helper\Data;


class HolidayView implements ArgumentInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ProductViewBlock
     */
    protected $productViewBlock;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        PriceHelper $priceHelper,
        RequestInterface $request,
        ProductViewBlock $productViewBlock
    ) {
        $this->productRepository = $productRepository;
        $this->priceHelper = $priceHelper;
        $this->request = $request;
        $this->productViewBlock = $productViewBlock;
    }

    /**
     * Get the current product
     *
     * @return \Magento\Catalog\Model\Product|null
     */
//    public function getProduct()
//    {
//        try {
//            $productId = $this->request->getParam('id');
//            return $this->productRepository->getById($productId);
//        } catch (\Exception $e) {
//            return null;
//        }
//    }
    public function getProduct()
    {
        return $this->productViewBlock->getProduct();
    }

    /**
     * Get the old price (regular price)
     *
     * @return string|null
     */
    public function getOldPrice()
    {
        $product = $this->getProduct();
        if ($product) {
            $oldPrice = $product->getData('price');
            return $this->priceHelper->currency($oldPrice, true, false);
        }
        return null;
    }

    /**
     * Get the new price (special price or final price)
     *
     * @return string|null
     */
    public function getNewPrice()
    {
        $product = $this->getProduct();
        if ($product) {
            return $this->priceHelper->currency($product->getFinalPrice(), true, false);
        }
        return null;
    }

    /**
     * Check if the product has a special price
     *
     * @return bool
     */
    public function hasSpecialPrice()
    {
        $product = $this->getProduct();

        if ($product) {
            return $product->getFinalPrice() < $product->getData('price');
        }
        return false;
    }

    /**
     * Check if the product has a holiday attribute
     *
     * @return bool
     */
    public function isHolidayProduct()
    {
        $product = $this->getProduct();
        return (bool) $product->getData('holiday_product');
    }
}
