define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'onlinepayment',
                component: 'Digicel_Online/js/view/payment/method-renderer/onlinepayment'
            }
        );
        return Component.extend({});
    }
);