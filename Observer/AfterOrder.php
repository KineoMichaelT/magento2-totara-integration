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

class AfterOrder implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Magento\Customer\Model\Session $_customerSession */
    protected $_customerSession;

    /** @var \Magento\Sales\Api\Data\OrderInterface $_order */
    protected $_order;

    /** @var \Magento\Framework\app\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    public function __construct(\Magento\Sales\Api\Data\OrderInterface $order, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
        $this->_order = $order;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_customerSession = $objectManager->create('Magento\Customer\Model\Session');

        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        $eventdata = $observer->getData();
        $orderids = http_build_query(['orderids' => $eventdata['order_ids']]);
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');

        $url = $wwwroot . '/local/magento/completeorder.php?' . $orderids;
        header('Location: ' . $url);
        die();
    }

}
