define(
    [
        'ko',
        'uiComponent',
        'underscore',
        'Magento_Checkout/js/model/step-navigator',
        'Magento_Checkout/js/model/full-screen-loader',
        'mage/storage',
        'Magento_Customer/js/model/customer',
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/model/shipping-rate-registry',
        'Magento_Checkout/js/action/get-totals',
        'Magento_Checkout/js/model/totals',
        'Magento_Checkout/js/model/cart/totals-processor/default',
        'Magento_Checkout/js/model/cart/cache'
    ],
    function (ko,
              Component,
              _,
              stepNavigator,
              fullScreenLoader,
              storage,
              customer,
              quote,
              rateRegistry,
              totals,
              getTotalsAction,
              defaultTotal,
              cartCache) {
        'use strict';
        /**
         * check-login - is the name of the component's .html template
         */
        return Component.extend({
            defaults: {
                template: "Perspective_CustomCheckoutStep/check-new"
    },
        //add here your logic to display step,
        isVisible: ko.observable(true),
            isVisibleDrop: ko.observable(false),
            isLogedIn: customer.isLoggedIn(),
            //step code will be used as step content id in the component template
            stepCode: 'newstep',
            //step title value
            stepTitle: "New Step",
            /**
             *
             * @returns {*}
             */
            initialize: function () {
            this._super();
            // register your step
            stepNavigator.registerStep(
                this.stepCode,
                //step alias
                null,
                this.stepTitle,
                //observable property with logic when display step or hide step
                this.isVisible,
                _.bind(this.navigate, this),
                /**
                 * sort order value
                 * 'sort order value' < 10: step displays before shipping step;
                 * 10 < 'sort order value' < 20 : step displays between shipping and payment step * 'sort order value' > 20 : step displays after payment step
                 */
                15
            );
            return this;
        },
        isStepDisplayed: function () {
            return true;
        },
        /**
         * The navigate() method is responsible for navigation between checkout step
         * during checkout. You can add custom logic, for example some conditions
         * for switching to your custom step
         */
        navigate: function () {
        },
        /**
         * @returns void
         */
        navigateToNextStep: function () {
            stepNavigator.next();
        }
    });
    }
);
