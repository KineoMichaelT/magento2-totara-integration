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

class AfterOrderSave implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Magento\Customer\Model\Session $_customerSession */
    protected $_customerSession;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;


    public function __construct(\Magento\Sales\Api\Data\OrderInterface $order, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_customerSession = $objectManager->create('Magento\Customer\Model\Session');
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $eventdata = $observer->getData();
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');

        /* @var $order \Magento\Sales\Model\Order */
        $order = ($eventdata['order']);

        $order->getItems();
        $order->getPayment()->getData();

        $orderdata = $order->getData();

        $orderdata['items'] = [];
        foreach ($order->getItems() AS $orderitem) {
            $orderdata['items'][] = $orderitem->getData();
        }
        $url = $wwwroot . '/local/magento/processorder.php';

        $orderdata['payment'] = $orderdata['payment']->getData();

        $s = curl_init();
        curl_setopt($s, CURLOPT_URL, $url);
        curl_setopt($s, CURLOPT_POSTFIELDS, json_encode($orderdata));
        curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($s);

        curl_close($s);

    }

}
