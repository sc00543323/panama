<?php

namespace Panama\InventorybyStore\Ui\Component\Listing\Column;

class Store extends \Magento\Ui\Component\Listing\Columns\Column {

    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Limesharp\Stockists\Model\StockistFactory $stocklist,
        array $components = [],
        array $data = []
    ){
        $this->stocklist = $stocklist;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $stocklist = $this->stocklist->create()->load($item['store_id']);
                $item['store_id'] = $stocklist['name']; //Here you can do anything with actual data
            }
        }

        return $dataSource;
    }
}