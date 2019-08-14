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
	$_item=$order->getAllItems();
		foreach ($order->getAllItems() as $item)
		{
			$productpi['id'] = $item->getId();
			$productpi['name'] = $item->getName();
			$productpi['type'] = $item->getProductType();
			$productpi['qty'] = $item->getQtyOrdered();
			$productpi['price'] = $item->getPrice();
			//$p = Mage::getModel("catalog/product")->load($item);
			
			
			$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
			$product = $objectManager->create('Magento\Catalog\Model\Product')->load($item->getId());
			$imagewidth=200;
			$imageheight=200;
			$imageHelper  = $objectManager->get('\Magento\Catalog\Helper\Image');
			$image_url = $imageHelper->init($product, 'product_page_image_small')->setImageFile($product->getFile())->resize($imagewidth, $imageheight)->getUrl();
			
			
			$productpi['sku'] = $item->getSku();
			$productpi['image'] = $image_url;
		}
		
		
	 $body = [
            'event' => 'order/created',
            'first_name'  => $order->getCustomerFirstname(),
			'last_name'  => $order->getCustomerLastname(),
			'email'  => $order->getCustomerEmail(),
			'city'  => $order->getShippingAddress()->getCity(),
			'province'  => $order->getShippingAddress()->getRegionId(),
			'country'  => $order->getShippingAddress()->getCountryId(),			
			'subtotal'  => $order->getSubtotal(),
			'grandtotal'  => $order->getGrandTotal(),
			'product' => $productpi,
			'amount_paid' => $order->getPayment()->getAmountPaid(),
			'payment_method' => $order->getPayment()->getMethod(),
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
		print_r($bodyJson);
		exit;
    }
}
