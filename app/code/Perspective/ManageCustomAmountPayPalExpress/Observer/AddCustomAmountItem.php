<?php

namespace Perspective\ManageCustomAmountPayPalExpress\Observer;

use \Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Event\Observer;
use \Magento\Checkout\Model\Session;

class AddCustomAmountItem implements ObserverInterface
{
    public $checkout;

    public function __construct(Session $checkout)
    {
        $this->checkout = $checkout;
    }

    public function execute(Observer $observer)
    {
        $cart = $observer->getEvent()->getCart();
        $quote = $this->checkout->getQuote();
        $address = $quote->getIsVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();

        if ($fee = $address->getCustomFee())
        {
            $cart->addCustomItem('Paypal Payment Fee', 1, $fee);
        }

        return $this;
    }
}
