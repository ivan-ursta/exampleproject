<?php

namespace Perspective\ChangePriceEvent\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class AdjustCollectionPrices implements ObserverInterface
{

    const PRICE_ADJUSTED_FLAG = 'price_adjusted';

    public function execute(Observer $observer)
    {
        $collection = $observer->getEvent()->getCollection();

        foreach ($collection as $product) {
            // Перевірка, чи ціна вже була змінена
            if ($product->getData(self::PRICE_ADJUSTED_FLAG)) {
                continue;
            }

            // Збільшення ціни на 20%
            $price = $product->getPrice();
            $finalPrice = $product->getFinalPrice();

            $adjustedPrice = $price * 1.2;
            $adjustedFinalPrice = $finalPrice * 1.2;

            $product->setPrice($adjustedPrice);
            $product->setFinalPrice($adjustedFinalPrice);

            // Встановлення флагу
            $product->setData(self::PRICE_ADJUSTED_FLAG, true);
        }
    }
}
