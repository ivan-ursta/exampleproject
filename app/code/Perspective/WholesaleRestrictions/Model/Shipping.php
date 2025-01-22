<?php

namespace Perspective\WholesaleRestrictions\Model;

use Magento\Quote\Model\Quote\Address\RateRequestFactory;
use Magento\Shipping\Model\Rate\PackageResultFactory;
use Magento\Shipping\Model\Rate\CarrierResultFactory;
use Magento\Quote\Model\Quote\Address\RateCollectorInterface;
use Perspective\WholesaleRestrictions\Helper\CustomerGroupHelper;

class Shipping extends \Magento\Shipping\Model\Shipping implements RateCollectorInterface
{
    protected $customerGroupHelper;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Shipping\Model\Config $shippingConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param \Magento\Shipping\Model\Rate\CarrierResultFactory $rateResultFactory
     * @param \Magento\Shipping\Model\Shipment\RequestFactory $shipmentRequestFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\Math\Division $mathDivision
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param RateRequestFactory $rateRequestFactory
     * @param PackageResultFactory|null $packageResultFactory
     * @param CarrierResultFactory|null $carrierResultFactory
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface   $scopeConfig,
        \Magento\Shipping\Model\Config                       $shippingConfig,
        \Magento\Store\Model\StoreManagerInterface           $storeManager,
        \Magento\Shipping\Model\CarrierFactory               $carrierFactory,
        \Magento\Shipping\Model\Rate\ResultFactory           $rateResultFactory,
        \Magento\Shipping\Model\Shipment\RequestFactory      $shipmentRequestFactory,
        \Magento\Directory\Model\RegionFactory               $regionFactory,
        \Magento\Framework\Math\Division                     $mathDivision,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        CustomerGroupHelper                                  $customerGroupHelper,
        RateRequestFactory                                   $rateRequestFactory = null,
        ?PackageResultFactory                                $packageResultFactory = null,
        ?CarrierResultFactory                                $carrierResultFactory = null
    )
    {
        parent::__construct(
            $scopeConfig,
            $shippingConfig,
            $storeManager,
            $carrierFactory,
            $rateResultFactory,
            $shipmentRequestFactory,
            $regionFactory,
            $mathDivision,
            $stockRegistry,
            $rateRequestFactory,
            $packageResultFactory,
            $carrierResultFactory
        );
        $this->customerGroupHelper = $customerGroupHelper;
    }

    public function collectRates(\Magento\Quote\Model\Quote\Address\RateRequest $request)
    {
        $customerSession = $this->customerGroupHelper->getCheckoutSession();
        $cartItemsQty = $customerSession->getQuote()->getItemsQty();
        $cartTotal = $customerSession->getQuote()->getGrandTotal();

        $groupId = $this->customerGroupHelper->getCustomerGroupId();
        $largeWholesaleGroupId = $this->customerGroupHelper->getLargeWholesaleGroupId();
        $wholesaleGroupId = $this->customerGroupHelper->getWholesaleGroupId();
        $minAmount = $this->customerGroupHelper->getLargeWholesaleMinAmount();
        $minQty = $this->customerGroupHelper->getWholesaleMinQty();

        parent::collectRates($request);

        $result = $this->getResult();
        $rates  = $result->getAllRates();


        $isFreeShippingOnly = false;
        if ($groupId == $largeWholesaleGroupId) {
            $isFreeShippingOnly = true;
        } else if ($groupId == $wholesaleGroupId && (int)$cartItemsQty > (int)$minQty) {
            $isFreeShippingOnly = true;
        }

        if ($isFreeShippingOnly) {
            $result->reset();
            $this->collectCarrierRates('freeshipping', $request);
        } else {
            $filteredRates = [];
            foreach ($rates as $rate) {
                if ($rate->getCarrier() === 'freeshipping') {
                    continue;
                }
                $filteredRates[] = $rate;
            }

            $result->reset();
            foreach ($filteredRates as $rate) {
                $result->append($rate);
            }
        }

        return $this;
    }
}
