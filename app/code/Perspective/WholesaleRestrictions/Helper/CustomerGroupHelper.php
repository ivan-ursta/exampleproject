<?php
declare(strict_types=1);

namespace Perspective\WholesaleRestrictions\Helper;

use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroup;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;

class CustomerGroupHelper
{
    /**
     * @var CustomerGroup
     */
    protected $customerGroup;

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var ScopeConfigInterface
     */
    protected ScopeConfigInterface $scopeConfig;


    public function __construct(
        Context $context,
        CustomerGroup $customerGroup,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        ScopeConfigInterface $scopeConfig

    ) {
        $this->customerGroup = $customerGroup;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns array of all groups, each element like ['value' => id, 'label' => name].
     */
    public function getCustomerGroups()
    {
        return $this->customerGroup->toOptionArray();
    }

    /**
     * Find a group ID by its label (group name).
     */
    public function getGroupIdByLabel(string $label): ?int
    {
        $groups = $this->getCustomerGroups();
        foreach ($groups as $group) {
            if (isset($group['label']) && $group['label'] === $label) {
                return (int)$group['value'];
            }
        }
        return null;
    }

    /**
     * Get the ID of the "Large Wholesale" group.
     */
    public function getLargeWholesaleGroupId(): ?int
    {
        return $this->getGroupIdByLabel('Large Wholesale');
    }

    /**
     * Get the ID of the "Wholesale" group.
     */
    public function getWholesaleGroupId(): ?int
    {
        return $this->getGroupIdByLabel('Wholesale');
    }

    public function getCheckoutSession()
    {
        return $this->checkoutSession;
    }

    public function getCustomerGroupId()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return '0';
        }

        $customer = $this->customerSession->getCustomer();

        return $customer->getGroupId();
    }

    public function getLargeWholesaleMinAmount() {
        return $this->scopeConfig->getValue(
            'sales/wholesale_restrictions/large_wholesale_amount_threshold',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getWholesaleMinQty() {
        return $this->scopeConfig->getValue(
            'sales/wholesale_restrictions/wholesale_qty_threshold',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getWholesaleShippingCode() {
        return $this->scopeConfig->getValue(
            'sales/wholesale_restrictions/wholesale_forced_shipping',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getLargeWholesalePaymentMethod() {
        return $this->scopeConfig->getValue(
            'sales/wholesale_restrictions/large_wholesale_payment_method',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getWholesalePaymentMethod() {
        return $this->scopeConfig->getValue(
            'sales/wholesale_restrictions/wholesale_forced_payment',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
