/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'ko',
    'underscore',
    'uiComponent',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Catalog/js/price-utils',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data',
    'moment'
], function (ko, _, Component, shippingService, priceUtils, quote, selectShippingMethodAction, checkoutData, moment) {
    'use strict';

    var mixin = {

        getExpectedDeliveryDate: function (shippingDeliveryDate) {
            return moment(shippingDeliveryDate).format("DD MMM YYYY");
        }
    };

    return function (target) {
        return target.extend(mixin);
    };

});
