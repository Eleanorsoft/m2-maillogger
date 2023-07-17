<?php

namespace Eleanorsoft\MailLogger\Controller\Index;

use Magento\Framework\App\Action\Context;
use Eleanorsoft\MailLogger\Model\LogRepositoryModel;
use Magento\Framework\Controller\ResultFactory;

class Message extends \Magento\Framework\App\Action\Action
{
    protected $logRepository;

    public function __construct(
        Context $context,
        LogRepositoryModel $logRepository
    )
    {
        parent::__construct($context);

        $this->logRepository = $logRepository;
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW);
        $resultJson->setHeader('Content-Type', 'text/html');

        $layout = $this->_view->getLayout();

        $block = $layout->createBlock(\Eleanorsoft\MailLogger\Block\Message::class);

        $resultJson->setContents($block->getMassage($this->getRequest()->getParam('id')));
        return $resultJson;


    }


    protected function createResult()
    {
        return $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
    }
}
