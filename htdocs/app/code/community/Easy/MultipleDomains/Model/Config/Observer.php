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
class Easy_MultipleDomains_Model_Config_Observer extends Mage_Core_Model_Abstract
{

    /**
     * Initialize Easy_MultipleDomains_Model_Resource_Config as resource class
     */
    protected function _construct()
    {
        $this->_init('easy_multipledomains/config');
    }

    /**
     * Observe config sections loading to add the correct number of domains
     * @param Varien_Event_Observer $observer
     */
    public function appendGroupsToSection($observer)
    {
        // If the section is the good one only
        if ((string) $observer->getSection()->tab == Easy_MultipleDomains_Helper_Data::SECTION_TAB) {
            // We read from from template file the skeleton of a domain group
            $xmlConfig = new Varien_Simplexml_Config(
                Mage::getModuleDir('etc', 'Easy_MultipleDomains') . DS . 'system.domain.template.xml'
            );

            // We append the correct number of groups to the config section
            for ($i = 1; $i <= Mage::helper('easy_multipledomains')->getNumberOfDomains(); $i++) {
                // We replace XXXXXX by the iterator
                $tmpStr = str_replace('XXXXXX', $i, $xmlConfig->getXmlString());

                // Append the group to section groups
                $observer->getSection()->groups->appendChild(
                    new Mage_Core_Model_Config_Element($tmpStr)
                );
            }
        }
    }

    /**
     * Observe when the easy_multipledomains config section is saved in admin
     * Then if the number of domains is reduced, we will remove overflow values
     * @param Varien_Event_Observer $observer
     */
    public function adminSystemConfigChanged($observer)
    {
        // Get the new number of domains
        $new = Mage::helper('easy_multipledomains')->getNumberOfDomains();
        /* @var $new int */

        // Get the max number of domains configs in database
        $old = $this->_getResource()->getDomainsMaxIncrement();
        /* @var $old int */

        // Example : $new = 5 and $old=10, we are going to clean config values
        // between 6 and 10 because they are no longer needed
        for ($i = $new + 1; $i <= $old; $i++) {
            // Remove older value
            $this->getResource()->deleteConfigData("easy_multipledomains/easy_multipledomains_$i/%");

            // Add a success message
            Mage::getSingleton('core/session')->addSuccess(
                Mage::helper('easy_multipledomains')->__('Domain %s configuration was successfully removed', $i)
            );
        }
    }
}
