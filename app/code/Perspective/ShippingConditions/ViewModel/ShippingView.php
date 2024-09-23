<?php

namespace Perspective\ShippingConditions\ViewModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Catalog\Block\Product\View as ProductViewBlock;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Perspective\ShippingConditions\Helper\Data;


class ShippingView implements ArgumentInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ProductViewBlock
     */
    private $productViewBlock;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        ProductViewBlock                    $productViewBlock,
        ProductAttributeRepositoryInterface $attributeRepository,
        CheckoutSession $checkoutSession,
        ProductRepositoryInterface $productRepository,
        Data $helper
    )
    {
        $this->productViewBlock = $productViewBlock;
        $this->attributeRepository = $attributeRepository;
        $this->checkoutSession = $checkoutSession;
        $this->productRepository = $productRepository;
        $this->helper = $helper;
    }

    public function isEnabled()
    {
        return $this->helper->isEnabled();
    }

    public function getCurrentProduct()
    {
        return $this->productViewBlock->getProduct();
    }

    /**
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getShippingConditionLabel()
    {
        $product = $this->getCurrentProduct();
        if (!$product) {
            return null;
        }

        $shippingConditionOptionId = $product->getData('shipping_conditions');
        if ($shippingConditionOptionId) {
            $attribute = $this->attributeRepository->get('shipping_conditions');
            $options = $attribute->getOptions();
            foreach ($options as $option) {
                if ($option->getValue() == $shippingConditionOptionId) {
                    return $option->getLabel();
                }
            }
        }

        return null;
    }

    /**
     * @return array|null
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getShippingFeeInCart()
    {
        $quote = $this->checkoutSession->getQuote();
        $items = $quote->getAllVisibleItems();

        $shippingData = [];

        foreach ($items as $item) {
            $product = $this->productRepository->getById($item->getProductId());

            $shippingConditionOptionId = $product->getData('shipping_conditions');

            if ($shippingConditionOptionId) {
                $attribute = $this->attributeRepository->get('shipping_conditions');
                $options = $attribute->getOptions();

                foreach ($options as $option) {
                    if ($option->getValue() == $shippingConditionOptionId) {
                        $shippingLabel = $option->getLabel();

                        $shippingData[] = [
                            'label' => $shippingLabel,
                            'fee' => $quote->getData('shipping_conditions_fee')
                        ];
                    }
                }
            }
        }

        return !empty($shippingData) ? $shippingData : null;
    }

}
