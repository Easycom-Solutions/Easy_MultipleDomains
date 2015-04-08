<?php

namespace Easy\MultipleDomains\Tests;

trait MultipleDomainsTestTrait
{
    protected function _setUp()
    {
        $this->start_url = 'http://md.easy.local/admin';
        
        if(!defined('PHPUNIT_COVERAGE_ENABLE')) {
            define('PHPUNIT_COVERAGE_ENABLE', getenv('PHPUNIT_COVERAGE_ENABLE') ? true : false);
        }

        if (PHPUNIT_COVERAGE_ENABLE) {
            $this->coverageScriptUrl = 'http://md.easy.local/phpunit_coverage.php';
        }
    }

    public function setUp()
    {
        $this->_setUp();
        parent::setUp(); 
    }

    public function testBackendSteps()
    {
        $test=$this;
        $this->prepareSession(); // Make the session available.
        $this->_testModuleInstallation();
        $this->_testToAddDomains();
        $this->_testRemovalOfDomains();
        $this->_testCleanUpOfConfigWhenRemoveADomain();
        $this->_testToSetZeroAsDomainNumber();
        $this->_populateDomain3();
    }

    public function testFrontendSteps()
    {
        $this->prepareSession();

        // Check that default theme is applyed on default domain
        $this->_testFrontEndStep('http://md.easy.local/', 'default/favicon.ico');

        // Check that modern theme is applyed on domain 3
        $this->_testFrontEndStep('http://md3.easy.local/', 'modern/favicon.ico');
    }

    // public function testSSL() {
    //     $this->prepareSession();
    //     $test = $this;
    //     $this->url('http://md.easy.local/');
    //     $this->byLinkText("ACCOUNT")->click();
    //     $this->byLinkText("My Account")->click();

    //     $test->assertTrue($this->_assert($test->byTag('html')->text(), 'LOGIN OR CREATE AN ACCOUNT'));

    //     sleep(5);
    // }


    public function testToRestore()
    {
        $this->prepareSession(); // Make the session available.
        $this->_loginToBackend();
        $this->_gotoSystemConfiguration();
        $this->_checkIfSectionExist($this);
        $this->_gotoMultipleDomainsSection();

        // Reset configuration
        $this->byId("easy_multipledomains_easy_multipledomains_3-head")->click();
        $this->_setDomainsTo('1');
    }

    /**
     * Recorded steps.
     */
    protected function _testModuleInstallation()
    {
        $test = $this;
        $this->_loginToBackend();
        $this->_gotoSystemConfiguration();
        $this->_checkIfSectionExist($this);
        $this->_gotoMultipleDomainsSection();

        $test->assertEquals("1", $test->byId("easy_multipledomains_general_number_of_domains")->value());
    }

    protected function _testToAddDomains()
    {
        $test = $this; // Workaround for anonymous function scopes in PHP < v5.4.
        $this->_setDomainsTo('3');

        // Test if the block of domain 3 is well created
        $test->assertTrue($this->_assert($test->source(), 'id="easy_multipledomains_easy_multipledomains_3-head"'));
    }

    protected function _testRemovalOfDomains()
    {
        $test = $this; // Workaround for anonymous function scopes in PHP < v5.4.

        $this->byId("easy_multipledomains_easy_multipledomains_3-head")->click();
        $this->_populateDomain3();
        $this->_setDomainsTo('2');

        // Test if domain removal message confirm is shown
        $test->assertTrue($this->_assert($test->byTag('html')->text(), "Domain 3 configuration was successfully removed"));

        // Test if the block of domain 3 is well removed
        $test->assertFalse($this->_assert($test->source(), 'id="easy_multipledomains_easy_multipledomains_3-head"'));
    }

    protected function _testCleanUpOfConfigWhenRemoveADomain()
    {
        $test = $this; // Workaround for anonymous function scopes in PHP < v5.4.
        // Define again the number of domain to 3
        $this->_setDomainsTo('3');

        // Check that previously populated data where removed on previous action
        $test->assertEquals('', $test->byId("easy_multipledomains_easy_multipledomains_3_base_url")->value());
        $test->assertEquals("0", $test->byXPath("//tr[@id='row_easy_multipledomains_easy_multipledomains_3_enabled']//select[normalize-space(.)='Yes No']")->value());
    }

    protected function _testToSetZeroAsDomainNumber()
    {
        $test = $this;
        // Try to define 0 as number of domain > that should not work
        $this->_setDomainsTo('0');

        // Test if the error message is shown
        $test->assertTrue($this->_assert($test->byTag('html')->text(), "The minimum value for Number Of Domains is 1, operation canceled for this value"));

        // Test if the value is well stayed to 3
        $test->assertEquals("3", $test->byId("easy_multipledomains_general_number_of_domains")->value());
    }

    protected function _testFrontEndStep($url, $icon)
    {
        $test = $this;
        $this->url($url);
        $test->assertTrue($this->_assert($test->source(), $icon));
    }

    protected function _loginToBackend()
    {
        $this->url($this->start_url);
        $this->_setTextValue('username', 'admin');
        $this->_setTextValue('login', 'password123');
        $this->byCssSelector("input.form-button")->click();
    }

    protected function _gotoSystemConfiguration()
    {
        $this->byLinkText("System")->click();
        $this->byLinkText("Configuration")->click();
    }

    protected function _checkIfSectionExist($test)
    {
        $test = $this;
        $test->assertTrue($this->_assert($test->byTag('html')->text(), "Multiple Domains"));
    }

    protected function _gotoMultipleDomainsSection()
    {
        $this->byLinkText("Multiple Domains")->click();
    }

    protected function _setDomainsTo($number)
    {
        $this->_setTextValue('easy_multipledomains_general_number_of_domains', $number);
        $this->_saveConfig();
    }

    protected function _saveConfig()
    {
        $this->byXPath("//div[@class='main-col-inner']//button[.='Save Config']")->click();
    }

    protected function _setTextValue($byId, $value)
    {
        $element = $this->byId($byId);
        $element->click();
        $element->clear();
        $element->value($value);
    }

    protected function _setYesNoValue($byXPath)
    {
        $element = $this->byXPath($byXPath);
        if (!$element->selected()) {
            $element->click();
        }
    }

    protected function _populateDomain3()
    {
        $this->_setYesNoValue("//tr[@id='row_easy_multipledomains_easy_multipledomains_3_enabled']//select[normalize-space(.)='Yes No']//option[1]");
        $this->_setTextValue('easy_multipledomains_easy_multipledomains_3_base_url', 'md3.easy.local');
        $this->_setTextValue('easy_multipledomains_easy_multipledomains_3_design_package', 'default');
        $this->_setTextValue('easy_multipledomains_easy_multipledomains_3_design_theme', 'modern');
        $this->_setYesNoValue("//tr[@id='row_easy_multipledomains_easy_multipledomains_3_disable_secure']//select[normalize-space(.)='Yes No']//option[1]");
        $this->_saveConfig();
    }

    protected function _assert($source, $assert)
    {
        try {
            $boolean = (strpos($source, $assert) !== false);
        } catch (\Exception $e) {
            $boolean = false;
        }
        return $boolean;
    }

    public function screenshot() 
    {
        $filedata = $this->currentScreenshot();
        file_put_contents($this->screenshotPath .'/'.$this->getName().'-'.time(). '.png', $filedata);
    }

}
