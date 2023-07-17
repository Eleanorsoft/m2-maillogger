<?php

namespace Eleanorsoft\MailLogger\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Mail\MessageInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Class Transport
 *
 * @package Eleanorsoft_MailLogger
 * @author <hello@eleanorsoft.com>
 * @copyright Copyright (c) 2023 Eleanorsoft (https://www.eleanorsoft.com/)
 */

class Transport extends \Magento\Email\Model\Transport
{
    /**
     * @var MessageInterface
     */
    protected $message;

    /**
     * Get "var" path
     *
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $dir;

    /**
     * For folder creation
     *
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $io;

    /**
     * For config values
     *
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LogFactory
     */
    protected $logFactory;

    /**
     * @var LogRepositoryModel
     */
    protected $logRepository;

    /**
     * @param MessageInterface $message Email message object
     * @param ScopeConfigInterface $scopeConfig Core store config
     * @param null|string|array|\Traversable $parameters Config options for sendmail parameters
     */
    public function __construct(
        MessageInterface $message,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\Filesystem\Io\File $io,
        \Eleanorsoft\MailLogger\Model\LogFactory $logFactory,
        \Eleanorsoft\MailLogger\Model\LogRepositoryModel $logRepository,
        $parameters = null
    ) {
        $this->message = $message;
        $this->dir = $dir;
        $this->io = $io;
        $this->scopeConfig = $scopeConfig;
        $this->logRepository = $logRepository;
        $this->logFactory = $logFactory;
        parent::__construct($message, $scopeConfig, $parameters);
    }

    public function sendMessage()
    {
        if ($this->isEnabled()) {
            $log = $this->logFactory->create();
            $cc = $this->message->getTo();
            if($cc) {
                $log->setReceiver($cc[0]->getEmail());
            }
            $log->setMessage($this->message->getRawMessage());

            try {
                $this->logRepository->save($log);
            }catch (\Exception $e){
                //
            }
        }

        if (!$this->isDisabledCommunication()) {
            parent::sendMessage();
        }else{
            $cc = $this->message->getTo();
            $whiteList = $this->emailWhiteList();
            if($cc){
                foreach ($cc as $receiver){
                    if(in_array($receiver->getEmail(), $whiteList)){
                        parent::sendMessage();
                    }
                }
            }
        }
    }

    protected function isEnabled()
    {
        return $this->scopeConfig->getValue("dev/maillogger/is_enabled");
    }

    protected function isDisabledCommunication()
    {
        return $this->scopeConfig->getValue("dev/maillogger/disable_mail_communication");
    }

    protected function emailWhiteList()
    {
        $whiteList = explode(',', $this->scopeConfig->getValue("dev/maillogger/email_whitelist") ?: '');
        return $whiteList;
    }
}
