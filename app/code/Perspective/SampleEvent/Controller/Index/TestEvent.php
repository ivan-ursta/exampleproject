<?php

namespace Perspective\SampleEvent\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\DataObject;

class TestEvent implements HttpGetActionInterface
{
    /**
     * @var ManagerInterface
     */
    protected $eventManager;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * Constructor
     *
     * @param ManagerInterface $eventManager
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        ManagerInterface $eventManager,
        ResultFactory $resultFactory
    ) {
        $this->eventManager = $eventManager;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Execute action and return result
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $textDisplay = new DataObject(['text' => 'Perspective']);
        $this->eventManager->dispatch('perspective_display_text', ['mp_text' => $textDisplay]);

        /** @var \Magento\Framework\Controller\Result\Raw $result */
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setContents($textDisplay->getText());
        return $result;
    }
}
