<?php

namespace Perspective\AutoRefreshCache\Model;

use Magento\Backend\App\Action\Context;
use Magento\Backend\App\Action;
use Magento\Framework\App\Cache\Manager as CacheManager;
use Magento\Framework\App\Cache\TypeListInterface as CacheTypeListInterface;
use Psr\Log\LoggerInterface;


class Cron
{

    protected CacheTypeListInterface $_cacheTypeList;
    protected \Magento\Framework\App\Cache\Frontend\Pool $_cacheFrontendPool;
    protected LoggerInterface $_logger;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool,
        LoggerInterface $logger
    )
    {
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        $this->_logger = $logger;
    }

    public function myCronFunction()
    {
        $invalidcache = $this->_cacheTypeList->getInvalidated();

        foreach ($invalidcache as $key => $value) {
            $this->_cacheTypeList->cleanType($key);
        }

        $this->_logger->info("AutoRefreshCache executed");
    }
}
