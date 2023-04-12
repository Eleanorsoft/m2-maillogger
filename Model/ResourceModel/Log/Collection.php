<?php

namespace Eleanorsoft\MailLogger\Model\ResourceModel\Log;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'log_id';
    protected $_eventPrefix = 'eleanorsoft_log_collection';
    protected $_eventObject = 'log_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Eleanorsoft\MailLogger\Model\Log', 'Eleanorsoft\MailLogger\Model\ResourceModel\Log');
    }
}
