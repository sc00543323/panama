<?php


namespace Digicel\DigicelToken\Model\ResourceModel\DigicelToken;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Digicel\DigicelToken\Model\DigicelToken',
            'Digicel\DigicelToken\Model\ResourceModel\DigicelToken'
        );
    }
}
