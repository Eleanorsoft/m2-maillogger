<?php

namespace Eleanorsoft\MailLogger\Block;

use Eleanorsoft\MailLogger\Model\LogRepositoryModel;

class Message extends \Magento\Framework\View\Element\Template
{
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        LogRepositoryModel $logRepository
    )
    {
        parent::__construct($context);
        $this->logRepository = $logRepository;
    }

    public function getMassage($id){

        $log = $this->logRepository->getById($id);

        $output  = preg_split("#\n\s*\n#Uis", $log->getMessage());


        $escapedMessage =
            "<details style='cursor: pointer'>
                <summary>Email Headers</summary>
                <div><pre>" . $this->headersEscape($output[0]) . "</pre></div>
            </details>";
        array_shift($output);
        $escapedMessage .=  utf8_decode(quoted_printable_decode(implode('<br>', $output)));

        return $escapedMessage;
    }

    private function headersEscape($headers){
        $headers = str_replace('<', '&lt;', $headers);
        $headers = str_replace('>', '&gt;', $headers);
        $headers = str_replace('=?utf-8?Q?', '', $headers);
        $headers = str_replace('=?UTF-8?Q?', '', $headers);
        $headers = str_replace('?= ', ' ', $headers);
        $headers = str_replace('=20', ' ', $headers);
        $headers = str_replace('?=', ' ', $headers);

        return $headers;
    }
}
