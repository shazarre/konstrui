<?php

namespace Konstrui\Logger;

use Colors\Color;

class StandardLogger implements LoggerInterface
{
    const LOG_LEVEL_NAME_DEBUG = 'DEBUG';
    const LOG_LEVEL_NAME_INFO = 'INFO';
    const LOG_LEVEL_NAME_WARNING = 'WARNING';
    const LOG_LEVEL_NAME_ERROR = 'ERROR';

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @param string $message
     * @param int    $level
     */
    public function log($message, $level = self::LEVEL_INFO)
    {
        $mappedLevelName = $this->mapLevelToReadableName($level);
        echo sprintf(
            "%s: %s%s\n",
            $mappedLevelName,
            $this->colorize($mappedLevelName, $this->prefix),
            $this->colorize($mappedLevelName, trim($message))
        );
    }

    /**
     * @param $level
     * @param $text
     */
    protected function colorize($level, $text)
    {
        if (empty($text)) {
            return $text;
        }
        
        $color = new Color($text);
        switch ($level) {
            case self::LOG_LEVEL_NAME_INFO:
                return $color->fg('green');
            case self::LOG_LEVEL_NAME_WARNING:
                return $color->fg('yellow');
            case self::LOG_LEVEL_NAME_ERROR:
                return $color->bg('red')->fg('white');
        }

        return $text;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @param int $level
     *
     * @return string
     */
    protected function mapLevelToReadableName($level)
    {
        $map = [
            self::LEVEL_DEBUG => self::LOG_LEVEL_NAME_DEBUG,
            self::LEVEL_INFO => self::LOG_LEVEL_NAME_INFO,
            self::LEVEL_WARNING => self::LOG_LEVEL_NAME_WARNING,
            self::LEVEL_ERROR => self::LOG_LEVEL_NAME_ERROR,
        ];

        if (array_key_exists($level, $map)) {
            return $map[$level];
        }

        return self::LOG_LEVEL_NAME_INFO;
    }
}
