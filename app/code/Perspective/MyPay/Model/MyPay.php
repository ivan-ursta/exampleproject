<?php

namespace Perspective\MyPay\Model;

use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Model\Quote\Address;

class MyPay extends AbstractMethod
{
    protected $_code = 'mypay';

    /**
     * Show MyPay only if CustomCartProductShipping is selected as the shipping method.
     */
//    public function isAvailable(CartInterface $quote = null)
//    {
//        if (!parent::isAvailable($quote)) {
//            return false;
//        }
//
//        if (!$quote) {
//            return false;
//        }
//
//        // Retrieve selected shipping method
//        /** @var Address $shippingAddress */
//        $shippingAddress = $quote->getShippingAddress();
//        if (!$shippingAddress) {
//            return false;
//        }
//
//        $selectedShippingMethod = $shippingAddress->getShippingMethod();
//
//        if (strpos($selectedShippingMethod, 'cartproductshipping_cartproductshipping') === false) {
//            return false;
//        }
//
//        // Otherwise, shipping is CustomCartProductShipping, so show MyPay
//        return true;
//    }
}
