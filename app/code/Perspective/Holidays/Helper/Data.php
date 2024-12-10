<?php

namespace Perspective\Holidays\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Perspective\Holidays\Model\ResourceModel\Holiday\CollectionFactory as HolidayCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Data extends AbstractHelper
{
    protected $holidayCollectionFactory;
    protected $storeManager;
    protected $logger;

    public function __construct(
        Context $context,
        HolidayCollectionFactory $holidayCollectionFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->holidayCollectionFactory = $holidayCollectionFactory;
        $this->storeManager = $storeManager;
        $this->logger = $logger;
    }

    /**
     * Check if today is within a holiday period
     *
     * @return bool
     */
    public function isHolidayPeriod()
    {
        return $this->getActiveHoliday() !== null;
    }

    /**
     * Get the active holiday record
     *
     * @return \Perspective\Holidays\Model\Holiday|null
     */
    public function getActiveHoliday()
    {
        try {
            $today = date('Y-m-d');
            $storeId = $this->storeManager->getStore()->getId();

            $collection = $this->holidayCollectionFactory->create();
            $collection->addFieldToFilter('is_active', 1);
            $collection->addFieldToFilter('store_id', ['in' => [0, $storeId]]);

            $connection = $collection->getConnection();
            $exactDateCondition = $connection->quoteInto('exact_date = ?', $today);
            $periodCondition = $connection->quoteInto('period_start <= ?', $today)
                . ' AND ' . $connection->quoteInto('period_end >= ?', $today);
            $combinedCondition = '(' . $exactDateCondition . ' OR (' . $periodCondition . '))';

            // Apply the condition to the collection
            $collection->getSelect()->where($combinedCondition);

            // Fetch the first matching holiday
            $holiday = $collection->setPageSize(1)->setCurPage(1)->getFirstItem();

            if ($holiday && $holiday->getId()) {
                return $holiday;
            }

            return null;
        } catch (\Exception $e) {
            $this->logger->error('Error fetching active holiday: ' . $e->getMessage());
            return null;
        }
    }

}
