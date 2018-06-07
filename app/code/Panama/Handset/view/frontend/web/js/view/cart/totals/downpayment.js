/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
define(
    [
        'Panama_Handset/js/view/checkout/summary/downpayment',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/totals'
    ],
    function (Component,quote, totals) {
        'use strict';

        return Component.extend({
            totals: quote.getTotals(),
            /**
             * @override
             */
            isDisplayed: function () {
                var price = 0;
                if (this.totals()) {
                    price = totals.getSegment('downpayment').value;
                }
                if(price == 0) {
                    return false;
                }
                else {
                    return true;
                }
            }
        });
    }
);