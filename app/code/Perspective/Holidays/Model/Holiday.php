<?php

namespace Perspective\Holidays\Model;

use Magento\Framework\Model\AbstractModel;
use Perspective\Holidays\Model\ResourceModel\Holiday as ResourceModel;

class Holiday extends AbstractModel
{
    /**
     * @var string
     */
    protected $_eventPrefix = 'perspective_holidays_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
