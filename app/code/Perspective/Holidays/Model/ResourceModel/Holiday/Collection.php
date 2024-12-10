<?php

namespace Perspective\Holidays\Model\ResourceModel\Holiday;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Perspective\Holidays\Model\Holiday as Model;
use Perspective\Holidays\Model\ResourceModel\Holiday as ResourceModel;

class Collection extends AbstractCollection
{
    protected $_eventPrefix = 'perspective_holidays_holiday_collection'; // Add this
    protected $_eventObject = 'holiday_collection';
    /**
     * Initialize collection model.
     */
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
