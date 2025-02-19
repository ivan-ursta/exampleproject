<?php
namespace Perspective\GeoLocation\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Perspective\GeoLocation\Model\IpLocator;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;


class GetCity extends Action
{
    protected $ipLocator;
    protected $resultJsonFactory;

    private $remoteAddress;

    public function __construct(
        Context $context,
        IpLocator $ipLocator,
        JsonFactory $resultJsonFactory,
        RemoteAddress $remoteAddress
    ) {
        $this->ipLocator = $ipLocator;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->remoteAddress = $remoteAddress;
        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

//        $ip = $this->remoteAddress->getRemoteAddress();                  // returns IP of a docker container
        $externalIp = file_get_contents('https://api.ipify.org');
        $city = $this->ipLocator->getCityByIp($externalIp);
        return $result->setData(['city' => $city]);
    }
}
