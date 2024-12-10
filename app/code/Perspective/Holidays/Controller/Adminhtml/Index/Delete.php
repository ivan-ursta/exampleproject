<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Perspective\Holidays\Model\HolidayFactory;
use Magento\Framework\Controller\Result\Redirect;

class Delete extends Action
{
    const ADMIN_RESOURCE = 'Perspective_Holidays::manage';

    /**
     * @var HolidayFactory
     */
    protected $holidayFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param HolidayFactory $holidayFactory
     */
    public function __construct(
        Action\Context $context,
        HolidayFactory $holidayFactory
    ) {
        parent::__construct($context);
        $this->holidayFactory = $holidayFactory;
    }

    /**
     * Delete Holiday
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('holiday_id');

        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($id) {
            try {
                $model = $this->holidayFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('The holiday has been removed.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['holiday_id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Holiday not found.'));
        return $resultRedirect->setPath('*/*/');
    }
}
