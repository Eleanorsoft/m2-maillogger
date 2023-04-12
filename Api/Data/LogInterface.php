<?php

namespace Eleanorsoft\MailLogger\Api\Data;

interface  LogInterface
{

    const LOG_ID = 'log_id';
    const RECEIVER = 'receiver';
    const CREATE_DATE = 'created_date';
    const MESSAGE = 'message';

    /**
     * @return int
     */
    public function getLogId(): int;
    /**
     * @return string
     */
    public function getReceiver(): string;
    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedDate(): string;

    /**
     *
     * @return string
     */
    public function getMessage(): string;


    /**
     * @param string $receiver
     * @return LogInterface
     */
    public function setReceiver(string $receiver): LogInterface;
    /**
     * @param string $createdDate
     * @return LogInterface
     */
    public function setCreateDate(string $createdDate): LogInterface;

    /**
     * @param string $message
     * @return LogInterface
     */
    public function setMessage(string $message): LogInterface;
}
