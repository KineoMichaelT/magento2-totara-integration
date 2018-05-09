<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Block\Cart\Item;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer {
    public function canShowQty() {
        return $this->_checkoutSession->getCustomerSession()->getBulkPurchase();
    }

    public function canShowPhoto() {
        return false;
    }
}