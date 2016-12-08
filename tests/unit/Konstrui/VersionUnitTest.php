<?php

namespace Konstrui;

use Konstrui\Version;

class VersionUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $version = new Version();
        $this->assertEquals('0.1.0', $version->getVersion());
    }
}
