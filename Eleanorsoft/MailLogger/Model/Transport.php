<?php

namespace Eleanorsoft\MailLogger\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;
use Magento\Store\Model\ScopeInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

/**
 * Class Transport
 * Log all email to a file.
 * Optionally disable all email communication.
 *
 * Default Magento2 email communication should be enabled!
 * Otherwise this model won't be invoked.
 *
 * @package Eleanorsoft_MailLogger
 * @author Konstantin Esin <hello@eleanorsoft.com>
 * @copyright Copyright (c) 2020 Eleanorsoft (https://www.eleanorsoft.com/)
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
     * @param MessageInterface $message Email message object
     * @param ScopeConfigInterface $scopeConfig Core store config
     * @param null|string|array|\Traversable $parameters Config options for sendmail parameters
     */
    public function __construct(
        MessageInterface $message,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem\DirectoryList $dir,
        \Magento\Framework\Filesystem\Io\File $io,
        $parameters = null
    ) {
        $this->message = $message;
        $this->dir = $dir;
        $this->io = $io;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($message, $scopeConfig, $parameters);
    }

    /**
     * Override default sendMessage().
     *
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    public function sendMessage()
    {
        if ($this->isEnabled()) {
            $file = $this->dir->getPath('var') . '/mail/' . time();
            $this->io->mkdir(dirname($file), 0775);
            try {
                file_put_contents($file, $this->message->getRawMessage());
            } catch (\Exception $e) {
                print $e->getMessage();exit;
            }
            if (!$this->isDisabledCommunication()) {
                parent::sendMessage();
            }
        } else {
            parent::sendMessage();
        }

    }

    /**
     * Check if module is enabled
     *
     * @return bool
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    protected function isEnabled()
    {
        return $this->scopeConfig->getValue("dev/maillogger/is_enabled");
    }

    /**
     * Check if email communication is disabled in settings
     *
     * @return bool
     * @author Konstantin Esin <hello@eleanorsoft.com>
     */
    protected function isDisabledCommunication()
    {
        return $this->scopeConfig->getValue("dev/maillogger/disable_mail_communication");
    }
}