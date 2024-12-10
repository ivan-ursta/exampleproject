<?php

namespace Perspective\Holidays\Ui\Component\Listing\Column\Store;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\System\Store;

class Options implements OptionSourceInterface
{
    protected $systemStore;

    public function __construct(
        Store $systemStore
    ) {
        $this->systemStore = $systemStore;
    }

    public function toOptionArray()
    {
        $options = [];

        $storeOptions = $this->systemStore->getStoreValuesForForm(false, true);
        $options = array_merge($options, $storeOptions);

        return $options;
    }
}
