<?php

namespace Fomo\Webhook\Controller\Adminhtml\System\Webhook;

class Edit extends \Fomo\Webhook\Controller\Adminhtml\System\Webhook
{
    /**
     * Edit Action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $webhook = $this->_initWebhook();

        $resultPage = $this->createPage();
        $resultPage->getConfig()->getTitle()->prepend(__('Fomo Webhooks'));
        $resultPage->getConfig()->getTitle()->prepend(
            $webhook->getId() ? $webhook->getCode() : __('New Fomo Webhook')
        );
        $resultPage->addContent($resultPage->getLayout()->createBlock('Fomo\Webhook\Block\System\Webhook\Edit'))
            ->addJs(
                $resultPage->getLayout()->createBlock(
                    'Magento\Framework\View\Element\Template',
                    '',
                    ['data' => ['template' => 'Fomo_Webhook::system/webhook/js.phtml']]
                )
            );
        return $resultPage;
    }
}
