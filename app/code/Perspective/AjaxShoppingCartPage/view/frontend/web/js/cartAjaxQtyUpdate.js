define([
    'jquery',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Customer/js/customer-data',
    'Magento_Checkout/js/model/cart/cache',
    'Magento_Checkout/js/model/cart/totals-processor/default',
    'Magento_Checkout/js/model/quote'
], function ($, getTotalsAction, customerData, cartCache, totalsProcessor, quote) {



    $(document).ready(function () {
        const formKey = window.checkoutConfig.formKey;
        var itemsCount = 0;
        updateItemsCount();

        function updateItemsCount() {
            itemsCount = $(document).find('.cart.item').length;
        }

        $(document).on('change', 'input[name$="[qty]"]', function () {
            var form = $('form#form-validate');
            $.ajax({
                url: form.attr('action'),
                data: form.serialize(),
                showLoader: true,
                success: function (res) {
                    var parsedResponse = $.parseHTML(res);
                    var result = $(parsedResponse).find("#form-validate");
                    var sections = ['cart'];

                    $("#form-validate").replaceWith(result);

                    // The mini cart reloading
                    customerData.reload(sections, true);

                    // The totals summary block reloading
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred);
                },
                error: function (xhr, status, error) {
                    var err = eval("(" + xhr.responseText + ")");
                    console.log(err.Message);
                }
            });
        });


        $(document).on('submit', '#discount-coupon-form', function (e) {
            e.preventDefault();

            let $form = $('#discount-coupon-form');
            $.ajax(
                {
                    type: "POST",
                    url: $form.attr('action'),
                    data: $form.serialize(),
                    success: function (response) {
                        cartCache.clear('cartVersion');
                        totalsProcessor.estimateTotals(quote.shippingAddress());
                    }
                }
            );
        });

        $(document).on("click", 'a.action-delete', function (e) {

            // Retrieve the JSON data from the data-post attribute
            var postData = $(this).data('post');
            if (!postData || !postData.data || !postData.data.id) {
                console.error('No valid item id found in data-post');
                return;
            }

            // Extend the post data to include the form key and mark it as an AJAX call.
            var requestData = $.extend({}, postData.data, {
                form_key: window.checkoutConfig.formKey,
                ajax: 1  // This flag tells Magento the request is AJAX
            });

            // You can use the URL and data from the postData object directly
            $.ajax({
                url: postData.action,
                method: 'POST',
                data: requestData,
                showLoader: true,
                success: function (response) {

                    // Replace the cart form with the updated version from the response.
                    var parsedResponse = $.parseHTML(response);
                    var result = $(parsedResponse).find("#form-validate");
                    $("#form-validate").replaceWith(result);

                    // Refresh mini-cart and totals
                    customerData.reload(['cart'], true);
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred);
                },
                error: function (xhr, status, error) {
                    console.error('Error removing item:', error);
                }
            });
        });

        $(document).on('change keydown', '.cart.item input.qty, .cart.item select.option-select', function (e) {
            if (e.type === 'keydown' && e.keyCode !== 13) {
                return;
            }

            e.preventDefault();

            let $productContainer = $(e.target).closest('.cart.item');
            if (!$productContainer.length) {
                return;
            }

            let params = getProductData($productContainer);
            let url = '/checkout/cart/updateItemOptions/id/' + params.item_id + '/';

            // You can use the URL and data from the postData object directly
            $.ajax({
                url: url,
                method: 'POST',
                data: params,
                showLoader: true,
                success: function (response) {

                    // Replace the cart form with the updated version from the response.
                    var parsedResponse = $.parseHTML(response);
                    var result = $(parsedResponse).find("#form-validate");
                    $("#form-validate").replaceWith(result);

                    // Refresh mini-cart and totals
                    customerData.reload(['cart'], true);
                    var deferred = $.Deferred();
                    getTotalsAction([], deferred);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });

        $(document).on("click", '.cart.item .action', function (e) {
            e.preventDefault();
        });

        function getProductData($productContainer){
            let params = {
                'qty' : $productContainer.find('input.qty').val() ?? 1,
                'product' : $productContainer.data('product_id') ?? null,
                'item_id' : $productContainer.data('item_id') ?? null,
                'form_key' : formKey
            }

            $productContainer.find('select.option-select').each(function(){
                params[$(this).attr('name')] = $(this).val();
            });

            return params;
        }

    });
});
