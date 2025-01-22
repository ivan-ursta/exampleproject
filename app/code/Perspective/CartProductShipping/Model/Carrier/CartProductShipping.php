<?php
declare(strict_types=1);

namespace Perspective\CartProductShipping\Model\Carrier;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory;
use Magento\Quote\Model\Quote\Address\RateResult\MethodFactory;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\ResultFactory;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\GroupRepositoryInterface;

class CartProductShipping extends AbstractCarrier implements CarrierInterface
{
    protected $_code = 'cartproductshipping';

    protected $_rateResultFactory;
    protected $_rateMethodFactory;

    protected $customerSession;

    protected $groupRepository;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ErrorFactory $rateErrorFactory,
        LoggerInterface $logger,
        ResultFactory $rateResultFactory,
        MethodFactory $rateMethodFactory,
        Session $customerSession,
        GroupRepositoryInterface $groupRepository,
        array $data = []
    ) {
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->customerSession = $customerSession;
        $this->groupRepository = $groupRepository;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $currentCustomerGroupId = (int)$this->customerSession->getCustomerGroupId();

        // If the user is not logged in, group ID might be 0 for "NOT LOGGED IN"
        if (!$currentCustomerGroupId) {
            return false;
        }

        try {
            $group = $this->groupRepository->getById($currentCustomerGroupId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return false;
        }

        if ($group->getCode() !== 'Pensioner') {
            return false;
        }

        // Get base cost from config
        $baseCost = (float)$this->getConfigData('base_cost');
        if ($baseCost <= 0) {
            $baseCost = 2.00; // fallback if not set
        }

        // Count physical items in the cart
        $itemCount = 0;
        foreach ($request->getAllItems() as $item) {
            if ($item->getProduct()->isVirtual()) {
                continue; // skip virtual items if needed
            }
            $itemCount += (int)$item->getQty();
        }

        // Determine discount percentage based on number of items
        $discountPercentage = 0;
        if ($itemCount == 2) {
            $discountPercentage = 20;
        } elseif ($itemCount == 3) {
            $discountPercentage = 30;
        } elseif ($itemCount >= 4) {
            $discountPercentage = 40;
        }

        $finalCost = $baseCost * ((100 - $discountPercentage) / 100);

        // Create shipping rate result
        $result = $this->_rateResultFactory->create();
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));
        $method->setMethod($this->_code);
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($finalCost);
        $method->setCost($finalCost);

        $result->append($method);

        return $result;
    }

    public function getAllowedMethods()
    {
        return [$this->_code => $this->getConfigData('name')];
    }
}
