define([
    'uiComponent',
    'Magento_Checkout/js/model/payment/renderer-list'
], function (
    Component,
    rendererList
) {
    'use strict';

    rendererList.push({
        type: 'mypay',
        component: 'Perspective_MyPay/js/view/payment/mypay'
    });

    return Component.extend({});
});
