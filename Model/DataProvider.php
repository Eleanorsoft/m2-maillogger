<?php

namespace Eleanorsoft\MailLogger\Model;

use Eleanorsoft\MailLogger\Model\ResourceModel\Log\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
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
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $logCollectionFactory,
        array $meta = [],
        array $data = []
    )
    {
        $this->collection = $logCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData(): array
    {
        $items = parent::getData();

        foreach ($items['items'] as $key => $item){
            $items['items'][$key]['message'] = utf8_decode(quoted_printable_decode($item['message']));
        }

        return $items;
    }
}
