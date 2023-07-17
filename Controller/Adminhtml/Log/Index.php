<?php

namespace Eleanorsoft\MailLogger\Controller\Adminhtml\Log;

class Index extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Eleanorsoft_MailLogger::logger');
        $resultPage->getConfig()->getTitle()->prepend((__('Emails')));

        return $resultPage;
    }
}
