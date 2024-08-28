var config = {
    map: {
        '*': {
            'Magento_Checkout/template/shipping-address/shipping-method-item':
                'SK_ShippingDeliveryDate/template/shipping-address/shipping-method-item',
            'Magento_Checkout/template/cart/shipping-rates':
                'SK_ShippingDeliveryDate/template/cart/shipping-rates'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/shipping': {
                'SK_ShippingDeliveryDate/js/view/shipping-mixin': true
            },
            'Magento_Checkout/js/view/cart/shipping-rates': {
                'SK_ShippingDeliveryDate/js/view/cart/shipping-rates-mixin': true
            }
        }
    }
};
