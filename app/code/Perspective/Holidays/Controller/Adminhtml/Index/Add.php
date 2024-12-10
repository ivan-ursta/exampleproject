<?php

namespace Perspective\Holidays\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Add extends Action
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
     */
    public function __construct(
        Action\Context $context,
        PageFactory    $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Create New Holiday
     */
    public function execute()
    {
        return $this->_forward('edit');
    }
}
