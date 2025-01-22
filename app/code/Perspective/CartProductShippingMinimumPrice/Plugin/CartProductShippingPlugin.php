<?php
declare(strict_types=1);

namespace Perspective\CartProductShippingMinimumPrice\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Shipping\Model\Rate\Result;
use Perspective\CartProductShipping\Model\Carrier\CartProductShipping;

class CartProductShippingPlugin
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * After plugin for collectRates
     *
     * @param CartProductShipping $subject
     * @param Result|bool $result
     * @return Result|bool
     */
    public function afterCollectRates(CartProductShipping $subject, $result): Result|bool
    {
        if (!$result instanceof Result) {
            return $result;
        }

        // Check if functionality is enabled from admin panel
        $isEnabled = $this->scopeConfig->isSetFlag(
            'carriers/cartproductshippingminimumprice/enabled',
            ScopeInterface::SCOPE_STORE
        );

        if (!$isEnabled) {
            return $result;
        }

        $methods = $result->getAllRates();
        foreach ($methods as $method) {
            $price = $method->getPrice();
            if ($price < 2.0) {
                $method->setPrice(2.0);
                $method->setCost(2.0);
            }
        }

        return $result;
    }
}
