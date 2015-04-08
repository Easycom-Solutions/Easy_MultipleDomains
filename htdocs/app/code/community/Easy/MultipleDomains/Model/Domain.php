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
class Easy_MultipleDomains_Model_Domain extends Mage_Core_Model_Config_Data
{

    protected $_collections = null;

    /**
     * Initialize Easy_MultipleDomains_Model_Resource_Config as resource class
     */
    protected function _construct()
    {
        $this->_init('easy_multipledomains/config');
    }

    /**
     * Get the list of db config rows that match the module prefix
     * @return type
     */
    public function getResourceCollection()
    {
        return parent::getResourceCollection()
                        ->addPathFilter('easy_multipledomains/easy_multipledomains_%');
    }

    /**
     * Return the array of domains as Varien_Object, or the whole list of configs
     * @param bool $asFriendly
     * @param bool $validOnly
     * @return array
     */
    public function getCollection($asFriendly = true, $validOnly = true)
    {
        if (!$this->_collections) {
            $this->_collections = $this->_getCollection();
        }
        if (!$asFriendly) {
            return $this->_collections['original'];
        }
        if ($validOnly) {
            return $this->_filterValidDomains($this->_collections['friendly']);
        }
        return $this->_collections['friendly'];
    }

    /**
     * Retrieve from db the collection of configs and create an array of Domains
     * @return array
     */
    protected function _getCollection()
    {
        if (!$collections = Mage::helper('easy_multipledomains/cache')->load('multipledomains_collections')) {
            $collections = array(
                'original' => array(),
                'friendly' => array()
            );

            $max = $this->_getResource()->getDomainsMaxIncrement();
            for ($i = 1; $i <= $max; $i++) {
                $collections['friendly'][$i] = new Varien_Object();
            }

            foreach ($this->getResourceCollection() as $key => $value) {
                $collections['original'][$key] = $value;
                $segs = explode('/', $value->getPath());
                $index = (int) str_replace('easy_multipledomains_', '', $segs[1]);

                $collections['friendly'][$index]
                        ->setData($segs[2], $value->getValue());
            }
            Mage::helper('easy_multipledomains/cache')->save($collections, 'multipledomains_collections');
        }
        return $collections;
    }

    /**
     * Remove unvalid domains from the friendly list
     * @param array $collection
     * @return array
     */
    protected function _filterValidDomains($collection)
    {
        foreach ($collection as $key => $value) {
            if (((bool) $value->getEnabled() === false) || ($value->getBaseUrl() === null)) {
                unset($collection[$key]);
            }
        }
        return $collection;
    }

}
