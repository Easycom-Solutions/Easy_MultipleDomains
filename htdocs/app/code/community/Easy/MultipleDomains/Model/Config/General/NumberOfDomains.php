<?php

/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Class that handle configuration change control on easy_multipledomains/general/number_of_domains config field updates
 */
class Easy_MultipleDomains_Model_Config_General_NumberOfDomains extends Mage_Core_Model_Config_Data
{

    const ERROR_MSG_MIN_VALUE = 'The minimum value for Number Of Domains is 1, operation canceled for this value';

    /**
     * Initialize Easy_MultipleDomains_Model_Resource_Config as resource class
     */
    protected function _construct()
    {
        $this->_init('easy_multipledomains/config');
    }

    /**
     * Check if the user don't try to set less than one domain
     * @return Easy_MultipleDomains_Model_Config_NumberOfDomains
     */
    public function save()
    {
        if (intval($this->getValue()) < 1) {
            Mage::helper('easy_multipledomains/data')->addError(self::ERROR_MSG_MIN_VALUE);
        } else {
            return parent::save();
        }
        return $this;
    }

}
