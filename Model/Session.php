<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Model;

class Session extends \Magento\Checkout\Model\Session {
    public function getCustomerSession() {
        return $this->_customerSession;
    }
}
