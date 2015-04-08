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
class Easy_MultipleDomains_Rewrite_Mage_Core_Model_Store extends Mage_Core_Model_Store
{

    /**
     * Return the baseurl for the cacheKey
     * @param string $cacheKey
     * @return string
     * @codeCoverageIgnore
     */
    public function getBaseUrlCache($cacheKey)
    {
        return $this->_baseUrlCache[$cacheKey];
    }

    /**
     * Store baseurl in cache for the current request
     * @param string $cacheKey
     * @param string $value
     */
    public function setBaseUrlCache($cacheKey, $value)
    {
        $this->_baseUrlCache[$cacheKey] = rtrim($value, '/') . '/';
        ;
    }

    /**
     * Define if theme is defined or not to avoid multiple load of this function
     * @var boolean
     */
    protected $_packageAndThemeDefined = false;

    /**
     * Retrieve base URL
     * We have to override this function to handle the ipad domain name; 
     * the default behavior is to define all links with de the base_url
     * If we are on a page with the ipad domain, we continue to use this domain.
     *
     * @param string $type
     * @param boolean|null $secure
     * @return string
     */
    public function getBaseUrl($type = self::URL_TYPE_LINK, $secure = null)
    {
        $url = parent::getBaseUrl($type, $secure);
        $cacheKey = $type . '/' . (is_null($secure) ? 'null' : ($secure ? 'true' : 'false')) . '-easymd';

        if (!isset($this->_baseUrlCache[$cacheKey])) {
            $params = array(
                'type' => $type,
                'secure' => $secure,
                'cache_key_original' => str_replace('-easymd', '', $cacheKey),
                'cache_key' => $cacheKey,
                'url' => $url,
                'source' => $this
            );

            Mage::dispatchEvent('store_get_base_url_after', $params);

            if (!isset($this->_baseUrlCache[$cacheKey])) {
                $this->setBaseUrlCache($cacheKey, $url);
            }
        }

        return $this->_baseUrlCache[$cacheKey];
    }

}
