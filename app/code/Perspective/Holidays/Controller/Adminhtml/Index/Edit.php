<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use Perspective\Holidays\Model\HolidayFactory;

class Edit extends Action
{
    const ADMIN_RESOURCE = 'Perspective_Holidays::manage';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var HolidayFactory
     */
    protected $holidayFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory    $resultPageFactory
     * @param HolidayFactory $holidayFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory    $resultPageFactory,
        HolidayFactory $holidayFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->holidayFactory = $holidayFactory;
    }

    /**
     * Edit Holiday
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('holiday_id');
        $model = $this->holidayFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This holiday no longer exists.'));
                return $this->_redirect('*/*/');
            }
        }

        // Set entered data if there was an error when saving
        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();

        // Build edit form
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getName() : __('New Holiday'));

        return $resultPage;
    }
}
