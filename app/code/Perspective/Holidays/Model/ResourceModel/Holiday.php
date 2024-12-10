<?php

namespace Perspective\Holidays\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Holiday extends AbstractDb
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'perspective_holidays_resource_model';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('perspective_holidays', 'holiday_id');
        $this->_useIsObjectNew = true;
    }
}
