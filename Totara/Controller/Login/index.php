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

use BitExpert\ForceCustomerLogin\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\RedirectInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var ScopeConfigInterface */
    protected $_scopeConfig;

    /** @var Session */
    protected $_session;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $session
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_session = $session;
        parent::__construct($context);
    }

    public function execute()
    {
        $totaraurl = $this->_scopeConfig->getValue('totara_config/url/root');
        $route = $this->_session->getAfterLoginReferer();
        $route = $route ?? 'checkout/cart/';

        $params = ['route' => $route];
        $loginurl = $totaraurl . '/local/magento/login.php?' . http_build_query($params);

        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($loginurl);
        return $resultRedirect;
    }
}
