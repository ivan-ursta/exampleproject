<?php

namespace Perspective\Holidays\Block\Adminhtml\Index\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class ResetButton implements ButtonProviderInterface
{
    /**
     * Get Reset button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'on_click' => 'location.reload();',
            'class' => 'reset',
            'sort_order' => 30,
        ];
    }
}
