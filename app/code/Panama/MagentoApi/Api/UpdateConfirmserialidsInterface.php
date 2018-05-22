<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\MagentoApi\Api;
/**
 * Update Confirmserialids in order item level
 */
interface UpdateConfirmserialidsInterface
{
   /**
     * @param \Panama\MagentoApi\Api\Data\ConfirmserialidsInterface $confirmserialidsData
     * @return \Panama\MagentoApi\Api\Data\ConfirmserialidsInterface
     */
    public function updateConfirmserialids(\Panama\MagentoApi\Api\Data\ConfirmserialidsInterface $confirmserialidsData);
}