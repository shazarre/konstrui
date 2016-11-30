<?php

namespace Konstruu\Logger;

/**
 * Trait with implementation of LoggerInterface.
 */
trait LoggingTrait
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param $log
     * @param int $level
     */
    public function log($log, $level = LoggerInterface::LEVEL_INFO)
    {
        if ($this->logger !== null) {
            $this->logger->log($log, $level);
        }
    }
}
