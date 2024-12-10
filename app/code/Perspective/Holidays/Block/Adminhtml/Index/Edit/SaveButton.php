<?php

namespace Perspective\Holidays\Block\Adminhtml\Index\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Save button data
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Holiday'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'button' => ['event' => 'save'],
                ],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}
