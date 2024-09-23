<?php

namespace Perspective\ShippingConditions\Model\Quote\Address\Total;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;
use Perspective\ShippingConditions\Helper\Data;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;



class ShippingConditionsFee extends AbstractTotal
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;


    public function __construct(
        Data $helper,
        ProductAttributeRepositoryInterface $attributeRepository,
        ProductRepositoryInterface $productRepository
    ) {
        $this->helper = $helper;
        $this->attributeRepository = $attributeRepository;
        $this->productRepository = $productRepository;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        if (!$this->helper->isEnabled()) {
            return $this;
        }
        $items = $quote->getAllVisibleItems();

        if (!count($items)) {
            return $this;
        }

        $fee = 0;

        foreach ($items as $item) {
            $product = $item->getProduct();
            $productId = $product->getId();

            $product = $this->productRepository->getById($productId, false, $quote->getStore()->getId());

            $shippingConditionOptionId = $product->getData('shipping_conditions');

            if ($shippingConditionOptionId) {
                $attribute = $this->attributeRepository->get('shipping_conditions');
                if($attribute) {
                    $conditionLabel = $attribute->getOptions();
                    foreach ($conditionLabel as $option) {
                        if ($option->getValue() == $shippingConditionOptionId) {
                            $label = $option->getLabel();
                            break;
                        }
                    }
                    $feePercent = $this->helper->getFeeByCondition($label ?? '');
                    if ($feePercent) {
                        $itemPrice = $item->getRowTotal();
                        $itemFee = ($itemPrice * $feePercent) / 100;
                        $fee += $itemFee;
                    }
                }
            }
        }

        $total->setTotalAmount('shipping_conditions_fee', $fee);
        $total->setBaseTotalAmount('shipping_conditions_fee', $fee);

        $total->setFee($fee);
        $total->setBaseFee($fee);

        $total->setGrandTotal($total->getGrandTotal());
        $total->setBaseGrandTotal($total->getBaseGrandTotal());

        $quote->setData('shipping_conditions_fee', $fee);

        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $fee = $quote->getData('shipping_conditions_fee');

        return [
            'code' => 'shipping_conditions_fee',
            'title' => __('Custom Shipping Fee'),
            'value' => $fee
        ];
    }

    public function getLabel()
    {
        return __('Shipping Conditions Fee');
    }
}
