#!/bin/bash
DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
RELEASE_PATH=$1
if [ -z $RELEASE_PATH ]; then 
	RELEASE_PATH=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && cd ../ && pwd ); 
fi

echo
echo "-------------------------------------------------"
echo "- Easycom Solutions - Patch Magento if required -"
echo "-------------------------------------------------"
echo

if [[ $MAGENTO_VERSION = 'magento-mirror-1.4.2.0' || $MAGENTO_VERSION = 'magento-mirror-1.5.1.0' || $MAGENTO_VERSION = 'magento-ce-1.6.2.0' ]]; then
	echo "-> Append adminhtml_block_system_config_init_tab_sections_before event for retro compatibility of the module (this event was created in Magento 1.7"
	patch ${RELEASE_PATH}/htdocs/app/code/core/Mage/Adminhtml/Block/System/Config/Tabs.php -i ${DIR}/patchs/Mage_Adminhtml_Block_System_Config_Tabs.patch
fi


exit 0