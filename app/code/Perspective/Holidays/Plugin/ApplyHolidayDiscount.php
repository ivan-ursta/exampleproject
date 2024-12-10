<?php

namespace Perspective\Holidays\Plugin;

use Magento\Catalog\Model\Product;
use Perspective\Holidays\Helper\Data as HolidayHelper;
use Magento\Customer\Api\GroupRepositoryInterface;
use Psr\Log\LoggerInterface;

class ApplyHolidayDiscount
{
    protected $holidayHelper;
    protected $customerSession;
    protected $groupRepository;
    protected $logger;

    public function __construct(
        HolidayHelper $holidayHelper,
        \Magento\Customer\Model\Session $customerSession,
        GroupRepositoryInterface $groupRepository,
        LoggerInterface $logger
    ) {
        $this->holidayHelper = $holidayHelper;
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        $this->logger = $logger;
    }

    /**
     * After plugin for getFinalPrice
     *
     * @param Product $subject
     * @param float $result
     * @return float
     */
    public function afterGetPrice(\Magento\Catalog\Model\Product $subject, $result)
    {
        try {
            // Fetch the active holiday
            $holiday = $this->holidayHelper->getActiveHoliday();
            if (!$holiday) {
                return $result;
            }

            // Get discount percentage
            $discountPercentage = $holiday->getDiscountPercentage();
            if ($discountPercentage <= 0) {
                return $result;
            }

            // Check if the product is marked as "holiday-eligible"
            $isHolidayProduct = $subject->getData('holiday_product');
            if (!$isHolidayProduct) {
                return $result; // Do not apply discount
            }

            // Get allowed customer groups
            $allowedGroupsStr = $holiday->getAllowedCustomerGroup();
            if (empty($allowedGroupsStr)) {
                return $result;
            }

            // Convert comma-separated string to array
            $allowedGroupsArray = explode(',', $allowedGroupsStr);
            $allowedGroupsArray = array_map('trim', $allowedGroupsArray);

            $customerGroupId = $this->customerSession->getCustomer()->getGroupId();

            // Check if current customer group is allowed
            if (!in_array($customerGroupId, $allowedGroupsArray)) {
                return $result;
            }

            // Apply discount
            $newPrice = $result * (1 - ($discountPercentage / 100));
            return $newPrice;
        } catch (\Exception $e) {
            $this->logger->error('Error applying holiday discount: ' . $e->getMessage());
            return $result;
        }
    }
}
