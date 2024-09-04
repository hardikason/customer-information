define([
    'jquery',
    'ko',
    'uiComponent',
    'mage/url',
], function(jQuery, ko, Component, url) {
    return Component.extend({

        initialize: function () {
            this._super();
            message = ko.observable('');
            isMessageVisible = ko.observable(false);
        },

        requestRemove: function (data, event) {

            var element = event.target;
            var orderId = element.getAttribute('order-id');
            var customerId = element.getAttribute('customer-id');

            jQuery.ajax({
                showLoader: true,
                url: url.build('customerinformation/index/removeRequest'),
                data: {
                    orderId: orderId,
                    customerId: customerId
                },
                type: "GET",
                dataType: 'json',
                success: (function (data) {

                    // Set the message text
                    parent.message(data.message);

                    // Show the message
                    parent.isMessageVisible(true);

                    if (data.success == true) {
                        jQuery('.message').addClass('success');
                    } else {
                        jQuery('.message').removeClass('success').addClass('error');
                    }

                    // Optionally, hide the message after a few seconds
                    setTimeout(function() {
                        parent.isMessageVisible(false);
                    }, 3000); // Hide after 3 seconds
                })
            });
        },

        toggleVisibility: function (data, event) {

            var element = event.currentTarget;
            var orderId = element.getAttribute('order-id');
            jQuery('.order-details').hide();
            jQuery('.order-details-'+orderId).slideToggle('slow');
        }
    });
});