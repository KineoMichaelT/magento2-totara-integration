<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Observer;

class RemoveItem implements \Magento\Framework\Event\ObserverInterface
{
    /** @var \GuzzleHttp\Client $_client */
    protected $_client;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \GuzzleHttp\Client $guzzleClient
    ) {
        $this->_client = $guzzleClient;
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        /* @var $quoteItem \Magento\Quote\Model\Quote\Item */
        $quoteItem = $observer->getQuoteItem();

        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');
        $token = $this->_scopeConfig->getValue('totara_config/webservice/token');

        $response = $this->_client->post($wwwroot . '/webservice/rest/server.php', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'wsfunction' => 'local_magento_delete_cartitem',
                'wstoken' => $token,
                'moodlewsrestformat' => 'json',
                'itemid' => $quoteItem->getItemId(),
            ],
        ]);

        $json = json_decode($response->getBody()->getContents());
        if (isset($json->exception)) {
            throw new \Exception($json->message);
        }

    }

}
