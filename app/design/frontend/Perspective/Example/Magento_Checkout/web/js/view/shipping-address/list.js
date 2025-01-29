/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
define([
    'underscore',
    'ko',
    'mageUtils',
    'uiComponent',
    'uiLayout',
    'Magento_Customer/js/model/address-list'
], function (_, ko, utils, Component, layout, addressList) {
    'use strict';

    var defaultRendererTemplate = {
        parent: '${ $.$data.parentName }',
        name: '${ $.$data.name }',
        component: 'Magento_Checkout/js/view/shipping-address/address-renderer/default',
        provider: 'checkoutProvider'
    };

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/shipping-address/list',
            visible: addressList().length > 0,
            rendererTemplates: []
        },

        /**
         * @inheritdoc
         */
        initialize: function () {
            this._super()
                .initChildren();

            // Watch for changes in addressList (added/removed addresses)
            addressList.subscribe(function (changes) {
                    var self = this;

                    changes.forEach(function (change) {
                        if (change.status === 'added') {
                            self.createRendererComponent(change.value, change.index);
                        }
                    });
                },
                this,
                'arrayChange'
            );

            return this;
        },

        /**
         * @inheritdoc
         */
        initConfig: function () {
            this._super();
            // The list of child components that are responsible for address rendering
            this.rendererComponents = [];

            return this;
        },

        /**
         * Render only the first address if addresses exist; otherwise, do nothing or render all if you prefer.
         */
        initChildren: function () {
            if (addressList().length > 0) {
                // Always render only the first address in the array
                this.createRendererComponent(addressList()[0], 0);
            } else {
                // If no addresses exist, we either do nothing or revert to the default loop
                // Uncomment the line below if you prefer to show *all* addresses (in case there's logic for multiple).
                // _.each(addressList(), this.createRendererComponent, this);
            }

            return this;
        },

        /**
         * Create new component that will render given address in the address list
         *
         * @param {Object} address
         * @param {*} index
         */
        createRendererComponent: function (address, index) {
            var rendererTemplate, templateData, rendererComponent;

            if (index in this.rendererComponents) {
                // If we already created a renderer for this index, just update the observable address
                this.rendererComponents[index].address(address);
            } else {
                // rendererTemplates are provided via layout
                rendererTemplate = address.getType() !== undefined && this.rendererTemplates[address.getType()] !== undefined ?
                    utils.extend({}, defaultRendererTemplate, this.rendererTemplates[address.getType()]) :
                    defaultRendererTemplate;

                templateData = {
                    parentName: this.name,
                    name: index
                };
                rendererComponent = utils.template(rendererTemplate, templateData);
                utils.extend(rendererComponent, {
                    address: ko.observable(address)
                });
                layout([rendererComponent]);
                this.rendererComponents[index] = rendererComponent;
            }
        }
    });
});
