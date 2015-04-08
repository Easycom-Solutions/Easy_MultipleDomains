<?php
/**
 * Easycom Solutions
 * @category  Easy
 * @package   Easy_MultipleDomains
 * @copyright 1995-2015 Easycom Solutions (http://www.groupe-easycom.com/)
 * @license   http://opensource.org/licenses/MIT  The MIT License (MIT)
 */

/**
 * Multiple Domains resource class to handle database interactions
 */
class Easy_MultipleDomains_Model_Resource_Config extends Mage_Core_Model_Mysql4_Config_Data
{
    protected $_maxIncrement = null;

    /**
     * Proceed to deletion of all config data vars that match the $likePath
     * @param string $likePath
     * @param string|null $scope
     * @return int
     */
    public function deleteConfigData($likePath, $scope = null)
    {
        $where = array('path like ?' => $likePath);
        if (!is_null($scope)) {
            $where['scope = ?'] = $scope;
        }
        return $this->_getWriteAdapter()->delete($this->getMainTable(), $where);
    }

    /**
     * Return the number of domains found in config table of database
     * @return int 
     */
    public function getDomainsMaxIncrement()
    {
        if (!isset($this->_maxIncrement)) {
            $this->_maxIncrement = $this->_getDomainsMaxIncrementFromCache();
        }
        return $this->_maxIncrement;
    }

    protected function _getDomainsMaxIncrementFromCache()
    {
        if(!$result = Mage::helper('easy_multipledomains/cache')->load('domains_max_increment')) {
            // Create the sql request to get the search value in a single statement
            $sql = "SELECT MAX("
                                . "CAST("
                                    . "LEFT("
                                        . "REPLACE(path, 'easy_multipledomains/easy_multipledomains_', ''), "
                                        . "LOCATE("
                                            . "'/', "
                                            . "REPLACE(path, 'easy_multipledomains/easy_multipledomains_', '')"
                                        . ") - 1"
                                    . ")"
                                . "as SIGNED)"
                            . ") "
                    . "FROM " . $this->getMainTable() . " "
                    . "WHERE path like 'easy_multipledomains/easy_multipledomains_%/%' ";

            // Use the PREPARE syntax because Zend surrender sql syntax with quotes
            $this->_getReadAdapter()
                    ->query(
                        'PREPARE ' . Easy_MultipleDomains_Helper_Data::SECTION_KEY . ' FROM :sql',
                        array('sql' => $sql)
                    );

            // Run msqyl EXECUTE to run the prepared sql statement
            $result = $this->_getReadAdapter()->fetchOne('EXECUTE '. Easy_MultipleDomains_Helper_Data::SECTION_KEY);
            
            // Save to cache
            Mage::helper('easy_multipledomains/cache')->save($result, 'domains_max_increment');
        }
        return $result;
    }
}
