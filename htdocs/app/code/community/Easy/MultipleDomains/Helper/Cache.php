<?php
/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Help to manipulate cache for module
 */
class Easy_MultipleDomains_Helper_Cache extends Mage_Core_Helper_Abstract
{
    /**
     * Cache tag is used to save data is the specified bean
     */
    const CACHE_TAG = 'CONFIG';

    /**
     * System cache instance
     * @var Mage_Core_Model_Cache 
     */
    protected $_cache;
    
    /**
     * Get the current store ID to store specific cache elements
     * @var int
     */
    protected $_storeId;

    /**
     * Inititalize necessary element for the helper like the cache instance
     */
    public function __construct()
    {
        $this->_cache = Mage::app()->getCacheInstance();
        $this->_storeId = Mage::app()->getStore()->getId();
    }

    /**
     * Load and unserialize data from easycom cache bean by id
     * @param string $key
     * @return object|null
     */
    public function load($key)
    {
        // If the cache bean is enable and the cache key is found
        if ($this->_cache->canUse(strtolower(self::CACHE_TAG)) && ($data = $this->_cache->load($this->formatKey($key)))) {
            // we deserialize the data
            $data = unserialize($data);
            return $data;
        }
        return null;
    }

    /**
     * Serialize and save data to the Easycom cache bean
     * @param object $data
     * @param string $key
     */
    public function save($data, $key)
    {
        // We add the data to cache bean
        if ($this->_cache->canUse(strtolower(self::CACHE_TAG))) {
            $this->_cache->save(
                serialize($data), 
                $this->formatKey($key), 
                array(self::CACHE_TAG), 
                60 * 60 * 24
            );
        }
        
    }

    /**
     * Append the cache key and the current store id to the given key
     * @param string $key
     * @return string
     */
    protected function formatKey($key)
    {
        return self::CACHE_TAG . '-' . $this->_storeId . '-' . $key;
    }

}