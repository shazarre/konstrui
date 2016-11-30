<?php

namespace Konstruu\Logger;

interface LoggableInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger);

    /**
     * @param $log
     *
     * @return mixed
     */
    public function log($log);
}
