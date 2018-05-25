<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\MagentoApi\Api;
/**
 * Update porting status in order level
 */
interface UpdatePortingstatusInterface
{
   /**
     * @param \Panama\MagentoApi\Api\Data\PortingstatusInterface $portingstatusData
     * @return \Panama\MagentoApi\Api\Data\PortingstatusInterface
     */
    public function updatePortingStatus(\Panama\MagentoApi\Api\Data\PortingstatusInterface $portingstatusData);
}