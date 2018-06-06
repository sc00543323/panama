<?php

namespace Panama\InventorybyStore\Model;

class Stores extends \Limesharp\Stockists\Model\Stores
{
    public function setNewStoreId($name)
    {
        return $this->setData('new_store_id', $name);
    }

    public function getNewStoreId()
    {
        return $this->getData('new_store_id');
    }

}
