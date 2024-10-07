<?php

namespace Eleanorsoft\MailLogger\Model;

use Eleanorsoft\MailLogger\Api\Data\LogInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractExtensibleModel;

/**
 *
 */
class Log extends AbstractExtensibleModel  implements IdentityInterface, LogInterface
{
    const CACHE_TAG = 'eleanorsoft_email_log';

    protected $_cacheTag = 'eleanorsoft_email_log';

    protected $_eventPrefix = 'eleanorsoft_email_log';

    protected function _construct()
    {
        $this->_init('Eleanorsoft\MailLogger\Model\ResourceModel\Log');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }


    public function getLogId(): int
    {
        return $this->getData(self::LOG_ID);
    }

    public function getReceiver(): string
    {
        return $this->getData(self::RECEIVER);
    }

    public function getCreatedDate(): string
    {
        return $this->getData(self::CREATE_DATE);
    }

    public function getMessage(): string
    {
        return $this->getData(self::MESSAGE);
    }

    public function setReceiver(string $receiver): LogInterface
    {
        return $this->setData(self::RECEIVER, $receiver);
    }

    public function setCreateDate(string $createdDate): LogInterface
    {
        return $this->setData(self::CREATE_DATE, $createdDate);
    }

    public function setMessage(string $message): LogInterface
    {
        return $this->setData(self::MESSAGE, $message);
    }
}
