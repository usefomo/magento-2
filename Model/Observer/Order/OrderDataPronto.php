<?php

namespace SweetTooth\Webhook\Model\Observer\Order;

use Magento\Framework\Event\Observer;

/**
 * Class Customer
 */
class OrderDataPronto implements \Magento\Framework\Event\ObserverInterface
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
   
	public function execute(\Magento\Framework\Event\Observer $observer)
    {
      $order = $observer->getEvent()->getOrder();
	  //print_r($order->getId());
		
	 $body = [
            'event' => 'order/created',
            'first_name'  => $order->getCustomerFirstname(),
			'last_name'  => $order->getCustomerLastname()
        ];
	
	 $webhooks = $this->_webhookFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('event', 'order/created');

        foreach($webhooks as $webhook)
        {
            $this->_sendWebhookdata($webhook->getUrl(), $body);
			//echo $webhook->getUrl();
        }
	 
    // print_r($order->getId());
		//exit;
    }
	protected function _sendWebhookdata($url, $body)
    {
		//$this->_logger->debug("Sending webhook for event " . $this->_getWebhookEvent() . " to " . $url);

        $bodyJson = $this->_jsonHelper->jsonEncode($body);

        $headers = ["Content-Type: application/json"];
        $this->_curlAdapter->write('POST', $url, '1.1', $headers, $bodyJson);
        $this->_curlAdapter->read();
        $this->_curlAdapter->close();
		
		$this->_logger->debug("Sending webhook for event " . $bodyJson . " to " . $url);
		
    }
}
