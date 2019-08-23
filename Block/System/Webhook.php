<?php

namespace Fomo\Webhook\Block\System;

/**
 * Webhook Block
 */
class Webhook extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Block constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Fomo_Webhook';
        $this->_controller = 'system_webhook';
        $this->_headerText = __('Fomo Webhooks');
        parent::_construct();
        $this->buttonList->update('add', 'label', __('Add New Fomo Webhook'));
    }
}
