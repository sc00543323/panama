<?php
/**
 * Copyright 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Panama\MagentoApi\Api;
/**
 * Update Invoice in order level
 */
interface UpdateInvoicesInterface
{
   /**
     * @param \Panama\MagentoApi\Api\Data\InvoicesInterface $invoicesData
     * @return \Panama\MagentoApi\Api\Data\InvoicesInterface
     */
    public function updateInvoices(\Panama\MagentoApi\Api\Data\InvoicesInterface $invoicesData);
}