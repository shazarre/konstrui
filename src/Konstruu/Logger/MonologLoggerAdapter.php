<?php

namespace Konstruu\Logger;

use Monolog\Logger;

class MonologLoggerAdapter implements LoggerInterface
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * MonologLoggerAdapter constructor.
     *
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function log($message, $level = self::LEVEL_INFO)
    {
        $this->logger->log($this->resolveMonologLogLevel($level), $this->prefix . $message);
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param $level
     *
     * @return int|mixed
     */
    protected function resolveMonologLogLevel($level)
    {
        $map = [
            self::LEVEL_DEBUG => Logger::DEBUG,
            self::LEVEL_INFO => Logger::INFO,
            self::LEVEL_WARNING => Logger::WARNING,
            self::LEVEL_ERROR => Logger::ERROR,
        ];

        if (array_key_exists($level, $map)) {
            $level = $map[$level];
        } else {
            $level = Logger::INFO;
        }

        return $level;
    }
}
