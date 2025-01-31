define([
    'Magento_Ui/js/form/form',
    'jquery'
], function (Component, $) {
    'use strict';

    return Component.extend({
        initialize: function () {
            this._super();
            return this;
        },

        onSubmit: function () {

            this.source.set('params.invalid', false);

            // call validation for customScope: 'myCustomForm'
            this.source.trigger('myCustomForm.data.validate');

            if (!this.source.get('params.invalid')) {

                var formData = this.source.get('myCustomForm');
                console.log('My custom form data:', formData);

            }
        }
    });
});
