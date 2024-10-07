<?php

namespace Eleanorsoft\MailLogger\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;

/**
 *
 */
class EmailCleanUp
{
    protected $logger;

    /**
     * @var \Eleanorsoft\MailLogger\Model\ResourceModel\Log\Collection
     */
    protected $collectionFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Eleanorsoft\MailLogger\Model\LogRepositoryModel
     */
    protected $logRepository;

    public function __construct(
        LoggerInterface $logger,
        \Eleanorsoft\MailLogger\Model\ResourceModel\Log\CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        \Eleanorsoft\MailLogger\Model\LogRepositoryModel $logRepository
    ) {
        $this->logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->collectionFactory = $collectionFactory;
        $this->logRepository = $logRepository;
    }

    /**
     * Write to system.log
     *
     * @return void
     */
    public function execute() {
        if(!$this->isCleanUpEnabled()){
            $this->logger->info('CleanUp Disabled by admin');
            return;
        }

        $this->logger->info('Cron Works');

        $startDate = new \DateTime();
        $startDate->modify('-' . $this->cleanUpPeriod() . ' day');

        $collection = $this->collectionFactory->create()
            ->addFieldToFilter('created_date', ['lteq' => $startDate->format('Y-m-d H:i:s')]);

        foreach ($collection as $item) {
            try{
                $this->logRepository->delete($item);
            }catch (\Exception $e){
                $this->logger->error($e->getMessage());
            }
        }
    }

    /**
     * @return int|mixed
     */
    protected function cleanUpPeriod()
    {
        return $this->scopeConfig->getValue("dev/maillogger/email_clean_period") ?? 90;
    }

    /**
     * @return bool
     */
    protected function isCleanUpEnabled(): bool
    {
        return $this->scopeConfig->getValue("dev/maillogger/enable_email_cleanup");
    }
}
