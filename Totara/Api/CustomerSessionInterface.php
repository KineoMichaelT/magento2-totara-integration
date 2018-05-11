<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Api;

interface CustomerSessionInterface
{
    /**
     * @param int $customerId
     * @param string $canBulkPurchase
     * @return string
     */
    public function login($customerid, $canBulkPurchase);

    /**
     * @param int $customerId
     * @return string
     */
    public function getToken($customerId);
}
