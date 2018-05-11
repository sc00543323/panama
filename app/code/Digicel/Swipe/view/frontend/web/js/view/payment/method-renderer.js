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
                type: 'custompayment',
                component: 'Digicel_Swipe/js/view/payment/method-renderer/custompayment'
            }
        );
        return Component.extend({});
    }
);