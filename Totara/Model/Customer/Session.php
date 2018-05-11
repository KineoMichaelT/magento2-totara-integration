<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Model\Customer;

use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;

class Session implements \Kineo\Totara\Api\CustomerSessionInterface
{

    /** @var \Magento\Customer\Model\Session $_customerSession */
    protected $customerSession;

    /** @var TokenModelFactory */
    private $tokenModelFactory;

    public function __construct(TokenModelFactory $tokenModelFactory) {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $this->tokenModelFactory = $tokenModelFactory;
    }

    public function getToken($customerId) {
        return $this->tokenModelFactory->create()->createCustomerToken($customerId)->getToken();
    }

    public function login($customerid, $canBulkPurchase) {
        $this->customerSession->loginById($customerid);
        $this->customerSession->setCanBulkPurchase($canBulkPurchase);
        return $this->customerSession->getSessionId();
    }



}
