<?php

/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * 
 */
class Easy_MultipleDomains_Rewrite_Mage_Core_Controller_Varien_Router_Standard extends Mage_Core_Controller_Varien_Router_Standard
{
    // <editor-fold defaultstate="collapsed" desc="Vars added for rewrite">

    /**
     * Used to defined if the usage of HTTPS need to be activate or not 
     * This override the default Magento behavior
     * @var bool
     */
    private $_forceSecure;

    /**
     * Get if the usage of HTTPS wil be forced
     * true = will force
     * false = will disable HTTPS
     * null : will use default Magento behavior
     * @return bool|null
     */
    public function getForceSecure()
    {
        return $this->_forceSecure;
    }

    /**
     * Set a value to enforce usage of HTTPS
     * @param bool $value
     */
    public function setForceSecure($value)
    {
        $this->_forceSecure = $value;
    }

    // </editor-fold>
    // 
    // <editor-fold defaultstate="collapsed" desc="Overrides functions">

    /**
     * Check whether URL for corresponding path should use https protocol
     *
     * @param string $path
     * @return bool
     */
    protected function _shouldBeSecure($path)
    {
        $params = array(
            "path" => $path,
            "router" => $this
        );

        // We dispatch the event, so any module can now handle traitement
        Mage::dispatchEvent('router_standard_url_should_secure_before', $params);

        if ($this->getForceSecure() === null) {
            return parent::_shouldBeSecure($path);
        }
        return $this->getForceSecure();
    }

    // </editor-fold>
}
