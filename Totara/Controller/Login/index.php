<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package Kineo
 * @subpackage Totara
 */

namespace Kineo\Totara\Controller\Login;

use Magento\Framework\App\Action\Context;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var \Magento\Customer\Model\Customer $_customer */
    protected $customer;

    /** @var \Magento\Customer\Model\Session $_customerSession */
    protected $customerSession;

    /** @var \Magento\Framework\View\Result\PageFactory $_resultPageFactory */
    protected $_resultPageFactory;

    /** @var \Magento\Framework\AppConfig\ScopeConfigInterface */
    protected $_scopeConfig;

    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\AppConfig\ScopeConfigInterface $scopeConfig,
        \Magento\Integration\Model\Oauth\Token $token
    ) {

        $customerToken = $tokenFactory->create();
        \Magento\Integration\Model\Oauth\Token::

        $this->_resultPageFactory = $resultPageFactory;
        $this->_scopeConfig = $scopeConfig;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $url = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $url->get('\Magento\Store\Model\StoreManagerInterface');
        $customerfactory = $objectManager->create('Magento\Customer\Model\CustomerFactory');
        $this->customer = $customerfactory->create();
        $this->customer->setWebsiteId($storeManager->getWebsite()->getWebsiteId());

        parent::__construct($context);
    }

    public function execute()
    {
        $totaraurl = $this->_scopeConfig->getValue('totara_config/url/root');


        $this->tok

        if ($this->getRequest()->) {

        }

        $email = $this->getRequest()->getParam('email');

        $this->customer->loadByEmail($email);
        if ($this->customer->getId() !== $this->customerSession->getId()) {
            // Only login if not logged in OR different user
            $this->customerSession->loginById($this->customer->getId());
        }

        $this->customerSession->setTotaraUrl($this->getRequest()->getParam('baseurl'));
        $this->customerSession->setReturnUrl($this->getRequest()->getParam('returnurl'));

        $this->customerSession->setBulkPurchase($this->getRequest()->getParam('canBulkPurchase'));

        $route = $this->getRequest()->getParam('route');
        $route = $route ?? 'checkout/cart/';

        $this->_redirect($route);
        return $this->_resultPageFactory->create();
    }

}
