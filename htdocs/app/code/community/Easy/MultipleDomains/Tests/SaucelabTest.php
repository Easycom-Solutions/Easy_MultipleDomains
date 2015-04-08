<?php

namespace Easy\MultipleDomains\Tests;

use Sauce\Sausage\WebDriverTestCase;

class SaucelabTest extends WebDriverTestCase
{
    use MultipleDomainsTestTrait;

    public static $browsers = array(
        array(
            'browserName' => 'firefox',
            'desiredCapabilities' => array(
                'platform' => 'OS X 10.10',
                'version' => '36.0',
                'screen-resolution' => '1024x768',
            )
        ),
    );
}
