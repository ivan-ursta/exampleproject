<?php

namespace Perspective\SystemConfigExample\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
    )
    {
        parent::__construct($context);
        $this->storeManager = $storeManager;
    }

    /*
     * @return bool
     */
    public function isEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->isSetFlag(
            'perspective/general/enabled',
            $scope
        );
    }
    /*
     * @return bool
     */
    public function isUahEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        if ($this->isEnabled($scope)) {
            return $this->scopeConfig->isSetFlag(
                'perspective/general/uah_enabled',
                $scope
            );
        }
        return false;
    }
    /*
     * @return bool
     */
    public function isEuroEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        if ($this->isEnabled($scope)) {
            return $this->scopeConfig->isSetFlag(
                'perspective/general/euro_enabled',
                $scope
            );
        }
        return false;
    }
    /*
     * @return float
     */
    public function getUahCourse($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/uah_course',
            $scope
        );
    }
    /*
     * @return float
     */
    public function getEuroCourse($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->getValue(
            'perspective/general/euro_course',
            $scope
        );
    }

    // for system currencies

    /**
     * @param $scope
     * @return bool
     */
    public function isSystemCurrenciesEnabled($scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT)
    {
        return $this->scopeConfig->isSetFlag(
            'perspective/system_currencies/enabled',
            $scope
        );
    }

    /**
     * Get enabled currencies in Magento.
     *
     * @return array
     */
    public function getAllowedCurrencies()
    {
        return $this->storeManager->getStore()->getAvailableCurrencyCodes(true);
    }

    /**
     * Get base currency code.
     *
     * @return string
     */
    public function getBaseCurrencyCode()
    {
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }

    /**
     * Get current currency code.
     *
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        return $this->storeManager->getStore()->getCurrentCurrencyCode();
    }
}
