<?php

namespace Eleanorsoft\MailLogger\Api;

use Eleanorsoft\MailLogger\Api\Data\LogInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteriaInterface;

interface LogRepositoryInterface
{
    /**
     * @param LogInterface $log
     * @return bool
     */
    public function save(LogInterface $log) : LogInterface;

    /**
     * @param int $logId
     * @return bool
     */
    public function getById(int $logId): LogInterface;

    /**
     * @param LogInterface $log
     * @return bool
     */
    public function delete(LogInterface $log): bool;

    /**
     * @param int $logId
     * @return bool
     */
    public function deleteById(int $logId): bool;
}
