<?php

namespace Perspective\Holidays\Block;

use Magento\Framework\View\Element\Template;
use Perspective\Holidays\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Store\Model\StoreManagerInterface;
use Perspective\Holidays\Helper\Data;

class Greeting extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    protected $storeManager;

    private $helper;


    /**
     * Constructor
     */
    public function __construct(
        Template\Context  $context,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        Data $helper,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function getGreetingMessage()
    {
        $holiday = $this->helper->getActiveHoliday();

        if ($holiday) {
            return $holiday->getGreetingText();
        }

        return null;
    }

    public function getDiscountInfo()
    {
        $holiday = $this->helper->getActiveHoliday();

        if ($holiday && $holiday->getDiscountPercentage() > 0) {
            return __('Discount: %1%', $holiday->getDiscountPercentage());
        }

        return null;
    }

}
