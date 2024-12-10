<?php

namespace Perspective\Holidays\Block\Adminhtml\Index\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * Get Delete button data
     *
     * @return array
     */
    public function getButtonData()
    {
        if ($this->getHolidayId()) {
            return [
                'label' => __('Delete Holiday'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to delete this holiday?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return [];
    }

    /**
     * Get URL for delete button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['holiday_id' => $this->getHolidayId()]);
    }
}
