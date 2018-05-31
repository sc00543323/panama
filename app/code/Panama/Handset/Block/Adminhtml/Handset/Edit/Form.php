<?php
namespace Panama\Handset\Block\Adminhtml\Handset\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
    protected $_systemStore;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
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

        $form->setHtmlIdPrefix('handset_');
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Add Handset Price'), 'class' => 'fieldset-wide']
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
            'plan_id',
            'text',
            [
                'name' => 'plan_id',
                'label' => __('Plan ID'),
                'id' => 'plan_id',
                'required' => true,
                'title' => __('Plan ID'),
            ]
        );

        $fieldset->addField(
            'monthly_service_price',
            'text',
            [
                'name' => 'monthly_service_price',
                'label' => __('Monthly Service Price'),
                'id' => 'monthly_service_price',
                'title' => __('Monthly Service Price'),
            ]
        );

        $fieldset->addField(
            'monthly_handset_price',
            'text',
            [
                'name' => 'monthly_handset_price',
                'label' => __('Monthly Hand set Price'),
                'id' => 'monthly_handset_price',
                'title' => __('Monthly Hand set Price'),
            ]
        );

        $fieldset->addField(
            'phone_sku',
            'text',
            [
                'name' => 'phone_sku',
                'label' => __('Phone SKU'),
                'id' => 'phone_sku',
                'required' => true,
                'title' => __('Phone SKU'),
            ]
        );

        $fieldset->addField(
            'previous_phone_price',
            'text',
            [
                'name' => 'previous_phone_price',
                'label' => __('Previous Phone Price (Prepaid)'),
                'id' => 'previous_phone_price',
                'title' => __('Previous Phone Price (Prepaid)'),
            ]
        );

        $fieldset->addField(
            'current_phone_price',
            'text',
            [
                'name' => 'current_phone_price',
                'label' => __('Current Phone Price(Prepaid)'),
                'id' => 'current_phone_price',
                'title' => __('Current Phone Price(Prepaid)'),
            ]
        );

        $fieldset->addField(
            'postpaid_phone_price',
            'text',
            [
                'name' => 'postpaid_phone_price',
                'label' => __('Postpaid Phone Price'),
                'id' => 'postpaid_phone_price',
                'title' => __('Postpaid Phone Price'),
            ]
        );

        $fieldset->addField(
            'prepaid_phone_price_with_port_in',
            'text',
            [
                'name' => 'prepaid_phone_price_with_port_in',
                'label' => __('Prepaid Phone price with port-in'),
                'id' => 'prepaid_phone_price_with_port_in',
                'title' => __('Prepaid Phone price with port-in'),
            ]
        );

        $fieldset->addField(
            'postpaid_phone_price_with_port_in',
            'text',
            [
                'name' => 'postpaid_phone_price_with_port_in',
                'label' => __('Postpaid Phone price with port-in'),
                'id' => 'postpaid_phone_price_with_port_in',
                'title' => __('Postpaid Phone price with port-in'),
            ]
        );

        $fieldset->addField(
            'valid_from',
            'date',
            [
                'name' => 'valid_from',
                'label' => __('Valid From'),
                'title' => __('Valid From'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );

        $fieldset->addField(
            'valid_to',
            'date',
            [
                'name' => 'valid_to',
                'label' => __('Valid To'),
                'title' => __('Valid To'),
                'date_format' => 'yyyy-MM-dd',
                'time_format' => 'hh:mm:ss'
            ]
        );

        $fieldset->addField(
            'color',
            'select',
            [
                'name' => 'color',
                'label' => __('Color'),
                'title' => __('Color'),
                'required' => false,
                'options' => ['0' => __('Green'), '1' => __('Yellow')]
            ],
            'to'
        );

        $fieldset->addField(
            'down_payment_amount',
            'text',
            [
                'name' => 'down_payment_amount',
                'label' => __('Down payment Amount'),
                'id' => 'down_payment_amount',
                'title' => __('Down payment Amount'),
                'display' => 'none'
            ],
            'color'
        );

        $fieldset->addField(
            'comment',
            'textarea',
            [
                'name' => 'comment',
                'label' => __('Comment'),
                'id' => 'comment',
                'title' => __('Comment'),
            ]
        );

        $form->setValues($model->getData());

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(
                'Magento\Backend\Block\Widget\Form\Element\Dependence'
            )->addFieldMap(
                "{$htmlIdPrefix}down_payment_amount",
                'down_payment_amount'
            )->addFieldMap(
                "{$htmlIdPrefix}color",
                'color'
            )->addFieldDependence(
                'down_payment_amount',
                'color',
                '1'
            )
        );

        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
