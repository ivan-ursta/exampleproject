<?php

namespace Perspective\NewGeoLocation\Model;

use Perspective\NewGeoLocation\Api\CityListInterface;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class CityListProvider
 * Retrieves a list of cities using the GeoDB Cities API.
 */
class CityListProvider implements CityListInterface
{
    /**
     * @var Curl
     */
    protected $curl;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * System config paths for GeoDB API.
     */
    protected $apiEndpointPath = 'geoip/api/geodb_endpoint';
    protected $apiKeyConfigPath  = 'geoip/api/geodb_key';

    public function __construct(
        Curl $curl,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->curl = $curl;
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getCities($countryCode = 'UA')
    {
        $endpoint = $this->scopeConfig->getValue($this->apiEndpointPath);
        $apiKey   = $this->scopeConfig->getValue($this->apiKeyConfigPath);
        if (!$endpoint || !$apiKey) {
            $this->logger->error('GeoDB API endpoint or key is not configured.');
            return [];
        }
        $url = $endpoint . '?countryIds=' . $countryCode;
        try {
            $this->curl->addHeader('x-rapidapi-key', $apiKey);
            $this->curl->addHeader('x-rapidapi-host', 'wft-geo-db.p.rapidapi.com');
            $this->curl->setTimeout(5);
            $this->curl->get($url);
            $response = $this->curl->getBody();
            $data = json_decode($response, true);
            return isset($data['data']) ? $data['data'] : [];
        } catch (\Exception $e) {
            $this->logger->error('Error fetching city list: ' . $e->getMessage());
            return [];
        }
    }
}
