<?php


namespace Digicel\DigicelToken\Model\ResourceModel;

class DigicelToken extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('digiceltoken', 'digiceltoken_id');
    }
}
