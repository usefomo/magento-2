<?php

namespace Fomo\Webhook\Controller\Adminhtml\System;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Mageplaza\Webhook\Model\Hook;
use Mageplaza\Webhook\Model\HookFactory;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Webhooks admin controller
 */
abstract class Webhook extends Action
{
     const ADMIN_RESOURCE = 'Fomo_Webhook::webhook';
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $_coreRegistry;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    public $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    public $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    public $layoutFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\View\LayoutFactory $layoutFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Initialize Layout and set breadcrumbs
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    
 
    protected function createPage()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Fomo_Webhook::system_webhook')
            ->addBreadcrumb(__('Fomo Webhooks'), __('Fomo Webhooks'));
        return $resultPage;
    }

    /**
     * Initialize Webhook object
     *
     * @return \Fomo\Webhook\Model\Webhook
     */
    protected function _initWebhook()
    {
        $webhookId = $this->getRequest()->getParam('webhook_id', null);
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        /* @var $webhook \Fomo\Webhook\Model\Webhook */
        $webhook = $this->_objectManager->create('Fomo\Webhook\Model\Webhook');
        if ($webhookId) {
            $webhook->setStoreId($storeId)->load($webhookId);
        }
        $this->_coreRegistry->register('current_webhook', $webhook);
        return $webhook;
    }

    /**
     * Check current user permission
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Fomo_Webhook::webhook');
    }
}
