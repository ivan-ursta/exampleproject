<?php

namespace Perspective\NewGeoLocation\Model;

use Perspective\NewGeoLocation\Api\GeoLocationInterface;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Class GeoLocation
 * Retrieves the city based on the client's IP using the ipstack API.
 */
class GeoLocation implements GeoLocationInterface
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
     * System config path for ipstack API key.
     *
     * @var string
     */
    protected $apiConfigPath = 'geoip/api/ipstack_key';

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
    public function getCityByIp($ip)
    {
        // Retrieve the API key from system configuration (securely stored)
        $apiKey = $this->scopeConfig->getValue($this->apiConfigPath);
        if (!$apiKey) {
            $this->logger->error('IPStack API key is not configured.');
            return null;
        }
        $url = sprintf('http://api.ipstack.com/%s?access_key=%s&fields=city', $ip, $apiKey);
        try {
            $this->curl->setTimeout(5);
            $this->curl->get($url);
            $response = $this->curl->getBody();
            $data = json_decode($response, true);
            return $data['city'] ?? null;
        } catch (\Exception $e) {
            $this->logger->error('Error fetching city by IP: ' . $e->getMessage());
            return null;
        }
    }
}
