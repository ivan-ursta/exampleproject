<?php
declare(strict_types=1);

namespace Perspective\WholesaleRestrictions\Plugin;

use Magento\Payment\Model\MethodList;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Perspective\WholesaleRestrictions\Helper\CustomerGroupHelper;

class PaymentMethodFilter
{
    private ScopeConfigInterface $scopeConfig;

    protected $customerGroupHelper;


    public function __construct(ScopeConfigInterface $scopeConfig, CustomerGroupHelper $customerGroupHelper)
    {
        $this->scopeConfig = $scopeConfig;
        $this->customerGroupHelper = $customerGroupHelper;
    }

    /**
     * Filter payment methods for "Large Wholesale" and "Wholesale"
     *
     * @param MethodList $subject
     * @param MethodInterface[] $result
     * @param CartInterface|null $quote
     * @return MethodInterface[]
     */
    public function afterGetAvailableMethods(MethodList $subject, array $result, CartInterface $quote = null)
    {
        if (!$quote) {
            return $result;
        }

        // Let's do similar logic, checking group ID, total, quantity
        $customerGroupId = (int)$quote->getCustomerGroupId();
        $grandTotal      = (float)$quote->getGrandTotal();
        $qtyInCart       = 0;
        foreach ($quote->getAllVisibleItems() as $item) {
            $qtyInCart += $item->getQty();
        }

        $largeWholesaleGroupId = $this->customerGroupHelper->getLargeWholesaleGroupId();
        $wholesaleGroupId = $this->customerGroupHelper->getWholesaleGroupId();

        //large wholesale
        $minAmount = $this->customerGroupHelper->getLargeWholesaleMinAmount();
        $largeWholesalePaymentMethod  = $this->customerGroupHelper->getLargeWholesalePaymentMethod();

        //wholesale
        $minQty = $this->customerGroupHelper->getWholesaleMinQty();
        $wholesalePaymentMethod = $this->customerGroupHelper->getWholesalePaymentMethod();

        if ($customerGroupId == $largeWholesaleGroupId) {
            if ($grandTotal > $minAmount && $largeWholesalePaymentMethod) {
                // keep only the selected payment method
                $result = array_filter($result, function ($method) use ($largeWholesalePaymentMethod) {
                    return $method->getCode() == $largeWholesalePaymentMethod;
                });
            }
        }
        elseif ($customerGroupId == $wholesaleGroupId) {
            if ($qtyInCart > $minQty && $wholesalePaymentMethod) {
                // force single method
                $result = array_filter($result, function ($method) use ($wholesalePaymentMethod) {
                    return $method->getCode() == $wholesalePaymentMethod;
                });
            }
        }

        return array_values($result);
    }
}
