<?php
namespace Perspective\NewGeoLocation\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;
use Perspective\NewGeoLocation\Api\GeoLocationInterface;

class GetCity implements HttpGetActionInterface
{
    /**
     * @var GeoLocationInterface
     */
    private $geoLocation;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var ClientInterface
     */
    private $httpClient;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        GeoLocationInterface $geoLocation,
        JsonFactory $resultJsonFactory,
        ClientInterface $httpClient,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        $this->geoLocation = $geoLocation;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->httpClient = $httpClient;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();
        $publicIp = $this->getPublicIp();

        try {
            $city = $publicIp ? $this->geoLocation->getCityByIp($publicIp) : null;
            return $result->setData(['city' => $city]);
        } catch (\Exception $e) {
            $this->logger->error('Geolocation Error: ' . $e->getMessage());
            return $result->setHttpResponseCode(500)->setData([
                'error' => 'Could not retrieve location information'
            ]);
        }
    }

    private function getPublicIp(): ?string
    {
        try {
            $this->httpClient->setTimeout(2);
            $this->httpClient->get('https://api.ipify.org');

            return filter_var(
                $this->httpClient->getBody(),
                FILTER_VALIDATE_IP,
                FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) ?: null;
        } catch (\Exception $e) {
            $this->logger->error('IP Lookup Failed: ' . $e->getMessage());
            return null;
        }
    }
}
