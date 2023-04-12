<?php

namespace Eleanorsoft\MailLogger\Model\ResourceModel;


class Log extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('eleanorsoft_email_log', 'log_id');
    }

}
