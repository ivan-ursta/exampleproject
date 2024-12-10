<?php

namespace Perspective\Holidays\Block\Adminhtml\Index\Edit;

use Magento\Backend\Block\Widget\Context;

class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * Constructor
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * Return model ID
     */
    public function getHolidayId()
    {
        return $this->context->getRequest()->getParam('holiday_id');
    }

    /**
     * Generate URL by route and parameters
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
