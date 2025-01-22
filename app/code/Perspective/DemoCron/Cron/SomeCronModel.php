<?php

namespace Perspective\DemoCron\Cron;

use Psr\Log\LoggerInterface;

class SomeCronModel
{
    protected $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    /**
     * Cronjob Description
     *
     * @return void
     */
    public function execute(): void
    {
        $this->logger->info("SomeCronModel executed");
    }
}
