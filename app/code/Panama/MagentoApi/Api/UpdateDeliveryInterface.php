<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\MagentoApi\Api;
/**
 * Update delivery status in order level
 */
interface UpdateDeliveryInterface
{
   /**
     * @param \Panama\MagentoApi\Api\Data\DeliveryInterface $deliveryData
     * @return \Panama\MagentoApi\Api\Data\DeliveryInterface
     */
    public function status(\Panama\MagentoApi\Api\Data\DeliveryInterface $deliveryData);
}