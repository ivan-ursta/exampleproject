<?php
declare(strict_types=1);

namespace Perspective\MyPay\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Disable MyPay if the selected shipping method is NOT CustomCartProductShipping
 */
class PaymentMethodAvailable implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    public function __construct(
        CheckoutSession $checkoutSession
    ) {
        $this->checkoutSession = $checkoutSession;
    }

    public function execute(Observer $observer)
    {
        // Payment method object
        $methodInstance = $observer->getEvent()->getMethodInstance();
        // The result object holding 'is_available'
        $result = $observer->getEvent()->getResult();

        // If not MyPay, do nothing.
        if ($methodInstance->getCode() !== 'mypay') {
            return;
        }

        // Retrieve the shipping method from the quote
        $quote = $this->checkoutSession->getQuote();
        if (!$quote || !$quote->getShippingAddress()) {
            return;
        }

        $shippingMethod = $quote->getShippingAddress()->getShippingMethod();

        // If it does NOT match, we disable MyPay
        if (strpos($shippingMethod, 'cartproductshipping_cartproductshipping') === false) {
            $result->setData('is_available', false);
        }
    }
}
