<?php

namespace Fomo\Webhook\Controller\Adminhtml\System\Webhook;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Index extends \Fomo\Webhook\Controller\Adminhtml\System\Webhook
{
    /**
     * Index Action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Fomo Webhooks'));
        return $resultPage;
    }
}
