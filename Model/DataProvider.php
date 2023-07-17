<?php

namespace Eleanorsoft\MailLogger\Model;

use Eleanorsoft\MailLogger\Model\ResourceModel\Log\CollectionFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\ReportingInterface;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\App\RequestInterface;

class DataProvider extends \Magento\Framework\View\Element\UiComponent\DataProvider\DataProvider
{

    private $_loadedData;

    private $dataPersistorInterface;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $quoteCollectionFactory
     * @param array $meta
     * @param array $data
     */
//    public function __construct(
//        $name,
//        $primaryFieldName,
//        $requestFieldName,
//        CollectionFactory $logCollectionFactory,
//        array $meta = [],
//        array $data = []
//    )
//    {
//        $this->collection = $logCollectionFactory->create();
//
protected $messageWrapper;
protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        ReportingInterface $reporting,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        RequestInterface $request,
        FilterBuilder $filterBuilder,
        CollectionFactory $logCollectionFactory,
        \Eleanorsoft\MailLogger\Controller\Index\Message $messageWrapper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $logCollectionFactory->create();
        $this->messageWrapper = $messageWrapper;
        $this->_storeManager = $storeManager;
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $reporting,
            $searchCriteriaBuilder,
            $request,
            $filterBuilder,
            $meta,
            $data
        );
    }

    public function getData(): array
    {
        $items = parent::getData();

        foreach ($items['items'] as $key => $item){
            $wrapperUrl = $this->_storeManager->getStore()->getBaseUrl();

            $items['items'][$key]['message'] = '<iframe
             src="'. $wrapperUrl . 'eleanorsoft_log/index/message/id/' . $items['items'][$key]['log_id'] .'"
             onload="javascript:(function(o){o.style.height=o.contentWindow.document.body.scrollHeight+\'px\';}(this));"
              style="height:200px;width:100%;border:none;overflow:hidden;"
             ></iframe>';
        }

        return $items;
    }
}
