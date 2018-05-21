<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\MagentoApi\Api;
use Panama\MagentoApi\Api\Data\InventoryInterface;
/**
 * Update Product Inventory 
 */
interface UpdateInventoryInterface
{
   /**
     * @param \Panama\MagentoApi\Api\Data\InventoryInterface $InventoryData
     * @return \Panama\MagentoApi\Api\Data\InventoryInterface
     */
    public function updateInventory(InventoryInterface $InventoryData );
}