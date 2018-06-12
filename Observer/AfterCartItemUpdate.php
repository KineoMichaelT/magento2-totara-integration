<?php
/**
 * @author Michael J. Trio <michael.trio@kineo.com>
 * @copyright 2018 Kineo
 */

namespace Kineo\Totara\Observer;

class AfterCartItemUpdate implements \Magento\Framework\Event\ObserverInterface
{

    /** @var \Magento\Checkout\Model\Cart $_order */
    protected $_cart;

    /** @var \GuzzleHttp\Client $_client */
    protected $_client;

    /** @var \Magento\Framework\App\Config\ScopeConfigInterface */
    protected $_scopeConfig;

    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \GuzzleHttp\Client $guzzleClient
    ) {
        $this->_cart = $cart;
        $this->_client = $guzzleClient;
        $this->_scopeConfig = $scopeConfig;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $wwwroot = $this->_scopeConfig->getValue('totara_config/url/root');
        $token = $this->_scopeConfig->getValue('totara_config/webservice/token');

        // TODO: Try out getData

        // Modify and build custom response because I can't figure out how to convert quote to standard WebAPI response.
        $payload = $this->_cart->getQuote()->toArray();
        foreach ($payload['items'] as $index => $cartitem) {
            $payload['items'][$index] = $cartitem->toArray();
        }
        $payload['customer'] = $this->_cart->getQuote()->getCustomer()->__toArray();
        $payload['id'] = $payload['entity_id'];

        $response = $this->_client->post($wwwroot . '/webservice/rest/server.php', [
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'wsfunction' => 'local_magento_update_cart',
                'wstoken' => $token,
                'moodlewsrestformat' => 'json',
                'quote' => json_encode($payload),
            ],
        ]);

        $json = json_decode($response->getBody()->getContents());
        if (isset($json->exception)) {
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
