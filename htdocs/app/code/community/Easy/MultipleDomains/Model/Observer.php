<?php

/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Observer that handle configuration change of number of domains
 */
class Easy_MultipleDomains_Model_Observer extends Mage_Core_Model_Abstract
{

    /**
     * Initialize Easy_MultipleDomains_Model_Resource_Config as resource class
     */
    protected function _construct()
    {
        $this->_init('easy_multipledomains/config');
    }

    /**
     * Listen on "router_standard_url_should_secure_before" event
     * It will disable HTTPS if required and apply design specified in config
     * @param type $observer
     * @return null
     */
    public function handleCheckSecureAndThemeUpdate($observer)
    {
        $list = Mage::getSingleton('easy_multipledomains/domain')->getCollection();

        foreach ($list as $domain) {
            if ($domain->getBaseUrl() == Mage::app()->getRequest()->getHttpHost()) {
                $this->_checkSecure($observer, $domain);
                $this->_applyTheme($domain);
                return;
            }
        }
    }

    /**
     * Listen on "store_get_base_url_after" event
     * Will accept the usage of a domain if it's enabled in config
     * @param type $observer
     * @return null
     */
    public function handleDomains($observer)
    {
        foreach (Mage::getSingleton('easy_multipledomains/domain')->getCollection() as $domain) {
            if ($domain->getBaseUrl() == Mage::app()->getRequest()->getHttpHost()) {
                $uri = parse_url($observer->getSource()->getConfig('web/' . ($observer->getSecure() ? 'secure' : 'unsecure') . '/base_url'));
                $url = $this->_disableSecureInUrlIfRequired(
                    str_replace($uri['host'], $domain->getBaseUrl(), $observer->getUrl()), $domain
                );
                $observer->getSource()->setBaseUrlCache($observer->getCacheKey(), $url);
                return;
            }
        }
    }

    /**
     * Replace https by http in $url if specified $domain is configured to disable https
     * @param string $url
     * @param Varien_Object $domain
     * @return string
     */
    protected function _disableSecureInUrlIfRequired($url, $domain)
    {
        if ((bool) $domain->getDisableSecure()) {
            $url = str_replace('https://', 'http://', $url);
        }
        return $url;
    }

    /**
     * Disable https on router if domain configuration require it
     * This is a subfunction of handleCheckSecureAndThemeUpdate()
     * @param type $observer
     * @param Varien_Object $domain
     */
    protected function _checkSecure($observer, $domain)
    {
        if ((bool) $domain->getDisableSecure()) {
            $observer->getRouter()->setForceSecure(false);
        }
    }

    /**
     * Apply a package and theme design if domain require it
     * This is a subfunction of handleCheckSecureAndThemeUpdate()
     * @param Varien_Object $domain
     */
    protected function _applyTheme($domain)
    {
        if ($this->_isset($domain->getDesignPackage()) && $this->_isset($domain->getDesignTheme())) {
            Mage::app()->loadArea('frontend');
            Mage::getDesign()->setArea('frontend')
                    ->setPackageName($domain->getDesignPackage())
                    ->setTheme($domain->getDesignTheme());
        }
    }

    protected function _isset($value)
    {
        return (isset($value) && !empty($value));
    }

}
