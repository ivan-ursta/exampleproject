<?php

namespace Perspective\ShippingConditions\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const XML_PATH_ENABLED = 'shipping_conditions/general/enabled';
    const XML_PATH_FEES = [
        'low temperature' => 'shipping_conditions/general/low_temperature',
        'ground transport' => 'shipping_conditions/general/ground_transport',
        'important documents' => 'shipping_conditions/general/important_documents',
        'fragile' => 'shipping_conditions/general/fragile',
    ];

    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    public function getFeeByCondition($conditionLabel)
    {
        if (isset(self::XML_PATH_FEES[$conditionLabel])) {
            return $this->scopeConfig->getValue(self::XML_PATH_FEES[$conditionLabel]);
        }
        return 0;
    }


}
