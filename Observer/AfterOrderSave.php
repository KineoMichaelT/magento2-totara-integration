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
    /** @var \GuzzleHttp\Client */
    private $_client;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    private $_scopeConfig;


    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \GuzzleHttp\Client $guzzleClient)
    {
        $this->_client = $guzzleClient;
        $this->_order =
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getOrder();
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');

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
