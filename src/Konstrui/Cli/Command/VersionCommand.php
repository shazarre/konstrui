<?php

namespace Konstrui\Cli\Command;

use Konstrui\Version;

/**
 * Prints out Konstrui version.
 */
class VersionCommand implements CommandInterface
{
    /**
     * @var Version
     */
    protected $version;

    /**
     * @param Version $version
     */
    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function run()
    {
        printf("Konstrui version %s\n", $this->version->getVersion());
    }
}
