<?php
/**
 * @copyright 2017 Kineo
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @package Kineo
 * @subpackage Totara
 */

namespace Kineo\Totara\Observer;

class AfterCartUpdate implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Magento\Checkout\Model\Cart $_order */
    protected $_cart;

    /** @var \Magento\Customer\Model\Session $_customerSession */
    protected $_customerSession;

    /** @var \Magento\Framework\App\ResponseFactory $_responseFactory */
    protected $_responseFactory;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    /** @var \Magento\Framework\UrlInterface $_url */
    protected $_url;


    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->_cart = $cart;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;

        $this->_scopeConfig = $scopeConfig;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_customerSession = $objectManager->create('Magento\Customer\Model\Session');
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');
        $url = $wwwroot . '/local/magento/refreshcart.php?id=' . $this->_cart->getQuote()->getId();

        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_exec($s);
        curl_close($s);

        $cartUrl = $this->_url->getUrl('checkout/cart/updatePost/');
        $this->_responseFactory->create()->setRedirect($cartUrl)->sendResponse();
    }

}
