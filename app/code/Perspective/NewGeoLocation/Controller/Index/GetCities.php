<?php

namespace Perspective\NewGeoLocation\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Perspective\NewGeoLocation\Api\CityListInterface;
use Magento\Framework\Controller\Result\JsonFactory;

/**
 * Class GetCities
 * AJAX endpoint to retrieve a list of cities.
 */
class GetCities extends Action
{
    /**
     * @var CityListInterface
     */
    protected $cityListProvider;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        CityListInterface $cityListProvider,
        JsonFactory $resultJsonFactory
    ) {
        $this->cityListProvider = $cityListProvider;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        // Retrieve cities for Ukraine ('UA')
        $cities = $this->cityListProvider->getCities('UA');
        return $result->setData(['cities' => $cities]);
    }
}
