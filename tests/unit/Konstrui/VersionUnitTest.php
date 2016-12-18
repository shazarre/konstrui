<?php

namespace Konstrui;

class VersionUnitTest extends \PHPUnit_Framework_TestCase
{
    public function testGetVersion()
    {
        $version = new Version();
        $this->assertEquals('0.4.0', $version->getVersion());
    }
}
