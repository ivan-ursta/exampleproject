<?php

namespace Perspective\OnlyXLeftModifier\Plugin;

use Magento\CatalogInventory\Block\Stockqty\AbstractStockqty;
use Magento\InventorySalesFrontendUi\Plugin\Block\Stockqty\AbstractStockqtyPlugin as MagentoAbstractStockqtyPlugin;

class StockQtyPlugin
{
    /**
     * Around plugin for Magento's aroundGetStockQtyLeft()
     *
     * @param MagentoAbstractStockqtyPlugin $subject
     * @param callable $proceed
     * @param AbstractStockqty $originalSubject
     * @param callable $originalProceed
     * @return float
     */
    public function aroundAroundGetStockQtyLeft(
        MagentoAbstractStockqtyPlugin $subject,
        callable $proceed,
        AbstractStockqty $originalSubject,
        callable $originalProceed
    ) {
        // Get the result from Magento's plugin
        $result = $proceed($originalSubject, $originalProceed);

        $modifiedResult = ceil($result / 2);

        return $modifiedResult;
    }
}
