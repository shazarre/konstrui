<?php

namespace Konstrui;

/**
 * Stores version string.
 */
class Version
{
    /** @var string **/
    protected $version = '0.2.0';

    /**
     * Returns current version of Konstrui.
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}
