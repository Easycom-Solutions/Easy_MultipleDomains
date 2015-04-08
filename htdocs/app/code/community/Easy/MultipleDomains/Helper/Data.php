<?php
/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Helper master class for the module, contains some constant and helper methods
 */
class Easy_MultipleDomains_Helper_Data extends Mage_Core_Helper_Abstract
{
    const SECTION_TAB = 'easy';
    const SECTION_KEY = 'easy_multipledomains';
    const NUMBER_OF_DOMAINS_KEY = 'easy_multipledomains/general/number_of_domains';
    
    /**
     * Add error to session of the user for notice only once
     * @param string $message
     */
    public function addError($message)
    {
        Mage::getSingleton('core/session')->addUniqueMessages(
            array(
                Mage::getSingleton('core/message')->error(
                    $this->__($message)
                )
            )
        );
    }

    /**
     * Get the number of domains defined in 'Muldiple domains' config section
     * @return int
     */
    public function getNumberOfDomains()
    {
        return Mage::getStoreConfig(self::NUMBER_OF_DOMAINS_KEY);
    }
}
