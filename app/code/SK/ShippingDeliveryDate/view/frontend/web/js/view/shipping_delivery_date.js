define([
    'uiComponent',
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/step-navigator',
    'moment'
], function (Component, ko, $, quote, stepNavigator, moment) {
    'use strict';
    return Component.extend({

        /**
         * @return {Boolean}
         */
        isVisible: function () {
            return !quote.isVirtual() && stepNavigator.isProcessed('shipping');
        },

        /**
         * @return {String}
         */
        getShippingDeliveryDate: function () {

            if (quote.shippingMethod() && quote.shippingMethod()['extension_attributes']['shipping_delivery_date']) {
                var shippingDeliveryDate = quote.shippingMethod()['extension_attributes']['shipping_delivery_date'];
                console.log(shippingDeliveryDate);
                return moment(shippingDeliveryDate).format("DD MMM YYYY");
            } else {
                return null;
            }
        }
    });
});