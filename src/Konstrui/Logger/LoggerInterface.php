<?php

namespace Konstrui\Logger;

interface LoggerInterface
{
    const LEVEL_DEBUG = 1;

    const LEVEL_INFO = 2;

    const LEVEL_WARNING = 3;

    const LEVEL_ERROR = 4;

    public function log($message, $level = self::LEVEL_INFO);

    public function setPrefix($prefix);
}
