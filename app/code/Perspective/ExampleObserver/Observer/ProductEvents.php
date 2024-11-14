<?php

namespace Perspective\ExampleObserver\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class ProductEvents implements ObserverInterface
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        //$myEventData = $observer->getData('myEventData');
        $product = $observer->getProduct();
        $originalName = $product->getName();
        $modifiedName = $originalName . ' - Content added by Perspective ';
        $product->setName($modifiedName);
    }
}
