define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/jquery.cookie'
], function($) {
    'use strict';
    return {
        init: function() {
            var self = this;
            // If there is no cookie with the selected city, we open the modal window automatically
            if (!$.cookie('selected_city')) {
                self.openModal();
            }
            // Open a modal window by clicking on a menu item
            $('#location-menu').on('click', function() {
                self.openModal();
            });
            // Processing a click on the "Select another" link
            $('#choose-another').on('click', function(e) {
                e.preventDefault();
                $('.city-dropdown').show();
            });
            // Handling a change in a drop-down list value
            $('#city-select').on('change', function() {
                var selectedCity = $(this).val();
                $.cookie('selected_city', selectedCity, { expires: 30, path: '/' });
                $('#location-city').text(selectedCity);
                $('#location-modal').modal('closeModal');
            });
        },
        openModal: function() {
            var self = this;
            // Initialize a modal window using Magento UI modal window
            $('#location-modal').modal({
                title: 'Choose your city',
                buttons: []
            }).modal('openModal');
            // Performing an AJAX request to determine the city via ipstack
            $.ajax({
                url: '/location/index/getcity',
                type: 'GET',
                dataType: 'json'
            }).done(function(response) {
                var city = response.city;
                if (city) {
                    $('#detected-city').text(city);
                } else {
                    $('#detected-city').text('Unable to determine city');
                }
            });
        }
    };
});
