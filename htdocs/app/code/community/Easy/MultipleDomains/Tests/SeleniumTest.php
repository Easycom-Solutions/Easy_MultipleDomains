<?php

namespace Easy\MultipleDomains\Tests;

class SeleniumTest extends \PHPUnit_Extensions_Selenium2TestCase
{
	protected $start_url = '';

	use MultipleDomainsTestTrait;

    protected function setUp()
    {
    	$this->_setUp();
        parent::setUp();

        $this->setBrowser('firefox');
        $this->setBrowserUrl($this->start_url); 
    }
}
