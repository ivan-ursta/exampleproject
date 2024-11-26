<?php

namespace Perspective\ChangePriceEvent\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AdjustQuoteTotals implements ObserverInterface
{

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $quote = $observer->getEvent()->getQuote();

        foreach ($quote->getAllItems() as $quoteItem) {
            $product = $quoteItem->getProduct();

            // Перевірка, чи ціна вже була змінена
            if ($product->getData('price_adjusted')) {
                continue;
            }

            // Збільшення ціни на 20%
            $price = $product->getFinalPrice();
            $adjustedPrice = $price * 1.2;

            // Встановлення нової ціни
            $quoteItem->setCustomPrice($adjustedPrice);
            $quoteItem->setOriginalCustomPrice($adjustedPrice);
            $quoteItem->getProduct()->setIsSuperMode(true);

            // Встановлення флагу
            $quoteItem->setData('price_adjusted', true);
        }
    }
}
