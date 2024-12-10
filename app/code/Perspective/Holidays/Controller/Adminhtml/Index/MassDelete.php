<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Perspective\Holidays\Model\ResourceModel\Holiday\CollectionFactory;
use Magento\Framework\Controller\ResultFactory;

class MassDelete extends Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Perspective_Holidays::manage';

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     */
    public function __construct(
        Action\Context     $context,
        Filter             $filter,
        CollectionFactory  $collectionFactory
    ) {
        parent::__construct($context);
        $this->filter             = $filter;
        $this->collectionFactory  = $collectionFactory;
    }

    /**
     * Execute action
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $holidayDeleted = 0;

        foreach ($collection as $holiday) {
            $holiday->delete();
            $holidayDeleted++;
        }

        $this->messageManager->addSuccessMessage(__('A total of %1 holiday(s) have been deleted.', $holidayDeleted));

        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('*/*/');
    }
}
