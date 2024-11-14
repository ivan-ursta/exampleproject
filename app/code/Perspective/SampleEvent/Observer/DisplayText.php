<?php

namespace Perspective\SampleEvent\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class DisplayText implements ObserverInterface
{

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $displayText = $observer->getData('mp_text');
        echo $displayText->getText() . " - Some content </br>";
        $displayText->setText('Execute event successfully');

        return $this;
    }
}
