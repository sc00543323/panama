<?php
namespace Panama\InventorybyStore\Block\Adminhtml\InventorybyStore\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Store\Model\System\Store $systemStore,
        \Limesharp\Stockists\Model\StockistFactory $stocklist,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->stocklist = $stocklist;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    protected function _prepareForm()
    {
        $dateFormat = $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT);
        $model = $this->_coreRegistry->registry('row_data');
        $form = $this->_formFactory->create(
            ['data' => [
                            'id' => 'edit_form',
                            'enctype' => 'multipart/form-data',
                            'action' => $this->getData('action'),
                            'method' => 'post'
                        ]
            ]
        );

        $form->setHtmlIdPrefix('inventory_');
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Add Inventory'), 'class' => 'fieldset-wide']
        );
        if($this->getRequest()->getParam('id')) {
            $fieldset->addField(
                'id',
                'hidden',
                [
                    'name' => 'id',
                    'label' => __('ID'),
                    'id' => 'id',
                    'title' => __('ID'),
                ]
            );
        }

        $fieldset->addField(
            'store_id',
            'select',
            [
                'name' => 'store_id',
                'label' => __('Store'),
                'id' => 'store_id',
                'required' => true,
                'title' => __('Store'),
                'values'   => $this->getStockists(),
            ]
        );

        $fieldset->addField(
            'sku',
            'text',
            [
                'name' => 'sku',
                'label' => __('SKU'),
                'id' => 'sku',
                'title' => __('SKU'),
            ]
        );

        $fieldset->addField(
            'quantity',
            'text',
            [
                'name' => 'quantity',
                'label' => __('Inventory'),
                'id' => 'quantity',
                'title' => __('Inventory'),
            ]
        );

        $form->setValues($model->getData());

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getStockists() {
        $stocklist = $this->stocklist->create()->getCollection();
        $values = array();
        foreach($stocklist as $stocklist) {
            $values[$stocklist['new_store_id']] = $stocklist['name'];
        }
        return $values;
    }
}
