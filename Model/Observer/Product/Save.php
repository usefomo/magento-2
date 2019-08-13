<?php

namespace SweetTooth\Webhook\Model\Observer\Product;

use Magento\Framework\Event\Observer;

/**
 * Class Customer
 */
class Save implements \Magento\Framework\Event\ObserverInterface
{
	
    /**
     * @var Logger
     */
    protected $_logger;

    /**
     * Curl Adapter
     */
    protected $_curlAdapter;

    /**
     * Json Helper
     * @var [type]
     */
    protected $_jsonHelper;

    /**
     * Webhook factory
     * @var [type]
     */
    protected $_webhookFactory;
	 public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\HTTP\Adapter\Curl $curlAdapter,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \SweetTooth\Webhook\Model\WebhookFactory $webhookFactory
    ) {
        $this->_logger = $logger;
        $this->_curlAdapter = $curlAdapter;
        $this->_jsonHelper = $jsonHelper;
        $this->_webhookFactory = $webhookFactory;
    }
    protected function _getWebhookEvent()
    {
        return 'product/updated';
    }

	public function execute(\Magento\Framework\Event\Observer $observer)
    {
     $order = $observer->getEvent()->getProduct();
	 
	 $body = [
            'event' => 'product/saved',
            'first_name'  => $order->getId(),
			'last_name'  => $order->getId()
        ];
	 
	 $webhooks = $this->_webhookFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('event', 'product/saved');

        foreach($webhooks as $webhook)
        {
            $this->_sendWebhookdata($webhook->getUrl(), $body);
        }
	 
     
    }
	protected function _sendWebhookdata($url, $body)
    {
        $this->_logger->debug("Sending webhook for event " . $this->_getWebhookEvent() . " to " . $url);

        $bodyJson = $this->_jsonHelper->jsonEncode($body);

        $headers = ["Content-Type: application/json"];
        $this->_curlAdapter->write('POST', $url, '1.1', $headers, $bodyJson);
        $this->_curlAdapter->read();
        $this->_curlAdapter->close();
		
    }
	
	/* 
    protected function _getWebhookData(Observer $observer)
    {
        $product = $observer->getEvent()->getProduct();
		print_r($product);
		exit;
        /**
         * TODO: Add some type of serialization which filters the
         * actual fields that get returned from the object. Returning
         * this raw data is dangerous and can expose sensitive data.
         *
         * Ideally this representation of the object will match that
         * of the json rest api. Maybe we can tap into that serializer?
         
        return [
            'product' => $product->getData()
        ];
    } */
}
