<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Perspective_Holidays::manage';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param Action\Context $context
     * @param PageFactory    $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory    $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Holidays list action
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Perspective_Holidays::manage');
        $resultPage->addBreadcrumb(__('Holidays'), __('Holidays'));
        $resultPage->getConfig()->getTitle()->prepend(__('Holidays'));

        return $resultPage;
    }
}
