<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Perspective\Holidays\Model\HolidayFactory;
use Magento\Framework\Controller\Result\Redirect;

class Save extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Perspective_Holidays::manage';

    /**
     * @var HolidayFactory
     */
    protected $holidayFactory;

    /**
     * Constructor
     */
    public function __construct(
        Action\Context $context,
        HolidayFactory $holidayFactory
    ) {
        parent::__construct($context);
        $this->holidayFactory = $holidayFactory;
    }

    /**
     * Save Holiday
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($data) {
            $id = $this->getRequest()->getParam('holiday_id');

            $model = $this->holidayFactory->create()->load($id);

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This holiday no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            // Handle allowed_customer_group array
            if (isset($data['allowed_customer_group']) && is_array($data['allowed_customer_group'])) {
                $data['allowed_customer_group'] = implode(',', $data['allowed_customer_group']);
            }

            $model->setData($data);

            try {
                // Validate overlapping periods
                $collection = $this->holidayFactory->create()->getCollection();
                $collection->addFieldToFilter('holiday_id', ['neq' => $id]);
                $collection->addFieldToFilter('is_active', 1);
                $collection->addFieldToFilter(
                    ['period_start', 'period_end'],
                    [
                        ['lteq' => $data['period_end']],
                        ['gteq' => $data['period_start']]
                    ]
                );

                if ($collection->getSize()) {
                    $this->messageManager->addErrorMessage(__('Holiday periods cannot overlap.'));
                    return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $id]);
                }

                $model->save();
                $this->messageManager->addSuccessMessage(__('The holiday is saved.'));
                $this->_session->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->_session->setFormData($data);
                return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $id]);
            }
        }

        return $resultRedirect->setPath('*/*/');
    }
}
