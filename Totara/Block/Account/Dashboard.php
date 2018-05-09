<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kineo\Totara\Block\Account;

class Dashboard extends \Magento\Customer\Block\Account\Dashboard {
    public function getBackUrl() {
        return $this->_scopeConfig->getValue('totara_config/url/root');
    }
}

