<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kineo\Totara\Block;

class Cart extends \Magento\Checkout\Block\Cart {

    public function canShowQty() {
        return $this->_customerSession->getBulkPurchase();
    }

    public function getContinueShoppingUrl() {
        return $this->_scopeConfig->getValue('totara_config/url/root') . '/totara/coursecatalog/courses.php';
    }

}