<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Observer;

class UpdateOrder implements \Magento\Framework\Event\ObserverInterface
{
    /** @var \GuzzleHttp\Client $_client */
    protected $_client;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \GuzzleHttp\Client $guzzleClient,
        \Magento\Quote\Model\Quote $quote
    ) {
        $this->_client = $guzzleClient;
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');
        $token = $this->_scopeConfig->getValue('totara_config/webservice/token');

        /* @var $order \Magento\Sales\Model\Order */
        $order = $observer->getOrder();
        $payload = $order->convertToArray();
        $payload['payment'] = $order->getPayment()->convertToArray();
        foreach ($payload['items'] as $index => $item) {
            $payload['items'][$index] = $item->convertToArray();
        }

        $response = $this->_client->post($wwwroot . '/webservice/rest/server.php', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'wsfunction' => 'local_magento_create_order',
                'wstoken' => $token,
                'moodlewsrestformat' => 'json',
                'order' => json_encode($payload),
            ],
        ]);

        $json = json_decode($response->getBody()->getContents());
        if (isset($json->exception)) {
            \error_log($json->message);
            throw new \Exception($json->message);
        }

        switch ($json->statusCode) {
            case 404:
                throw new \Exception('Error 404: Resource not found.');
            case 500:
                throw new \Exception('Error 500: Internal server error.');
        }
    }

}
