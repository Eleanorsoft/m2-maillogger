<?php

namespace Eleanorsoft\MailLogger\Model;

use Eleanorsoft\MailLogger\Api\Data\LogInterface;

use Eleanorsoft\MailLogger\Api\Data\LogInterfaceFactory;
use Eleanorsoft\MailLogger\Model\ResourceModel\Log as ResourceLog;
use Eleanorsoft\MailLogger\Model\ResourceModel\Log\CollectionFactory as LogCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class LogRepositoryModel implements \Eleanorsoft\MailLogger\Api\LogRepositoryInterface
{

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var ResourceLog
     */
    private $resource;
    /**
     * @var LogCollectionFactory
     */
    private $collectionFactory;
    /**
     * @var LogFactory
     */
    private $logFactory;
    /**
     * @var LogInterfaceFactory
     */
    private $logInterfaceFactory;

    /**
     * @param ResourceLog $resource
     * @param LogFactory $logFactory
     * @param LogInterfaceFactory $logInterfaceFactory
     * @param LogCollectionFactory $collectionFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        ResourceLog                      $resource,
        LogFactory                       $logFactory,
        LogInterfaceFactory              $logInterfaceFactory,
        LogCollectionFactory             $collectionFactory,
        CollectionProcessorInterface      $collectionProcessor
    ) {
        $this->resource = $resource;
        $this->logFactory = $logFactory;
        $this->collectionFactory = $collectionFactory;
        $this->logInterfaceFactory = $logInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
    }

    public function save(LogInterface $log): LogInterface
    {
        try {
            $this->resource->save($log);
        } catch (LocalizedException $exception) {
            throw new CouldNotSaveException(
                __('Could not save the log: %1', $exception->getMessage()),
                $exception
            );
        } catch (\Throwable $exception) {
            throw new CouldNotSaveException(
                __('Could not save the log: %1', __('Something went wrong while saving the log.')),
                $exception
            );
        }

        return $this->getById($log->getLogId());
    }

    public function getById(int $logId): LogInterface
    {
        $quote = $this->logFactory->create();
        $this->resource->load($quote, $logId);
        if (!$quote->getId()) {
            throw new NoSuchEntityException(__('The log with the "%1" ID doesn\'t exist.', $logId));
        }
        return $quote;
    }

    public function delete(LogInterface $log): bool
    {
        try {
            $this->resource->delete($log);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(
                __('Could not delete the log: %1', $exception->getMessage())
            );
        }
        return true;
    }

    /**
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById(int $logId): bool
    {
        return $this->delete($this->getById($logId));
    }
}
