define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/jquery.cookie'
], function($) {
    'use strict';
    return {
        init: function() {
            var self = this;
            // Open modal automatically if no city is selected
            if (!$.cookie('selected_city')) {
                self.openModal();
            }
            $('#location-menu').on('click', function() {
                self.openModal();
            });
            $('#choose-another').on('click', function(e) {
                e.preventDefault();
                // Load city list if not already loaded
                self.loadCityList();
                $('.city-dropdown').show();
            });
            $('#city-select').on('change', function() {
                var selectedCity = $(this).val();
                $.cookie('selected_city', selectedCity, { expires: 30, path: '/' });
                $('#location-city').text(selectedCity);
                $('#location-modal').modal('closeModal');
            });
        },
        openModal: function() {
            var self = this;
            $('#location-modal').modal({
                title: 'Choose your city',
                buttons: []
            }).modal('openModal');
            // AJAX call to get detected city via our GetCity controller
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
                    $('#city-error').text('Automatic detection failed. Please choose your city from the list.').show();
                    self.loadCityList();
                    $('.city-dropdown').show();
                }
            }).fail(function() {
                $('#detected-city').text('Request error');
                $('#city-error').text('Error determining city. Please choose manually.').show();
                self.loadCityList();
                $('.city-dropdown').show();
            });
        },
        loadCityList: function() {
            // If the dropdown is already populated, do not fetch again.
            if ($('#city-select option').length > 1) {
                return;
            }
            // AJAX call to get the list of cities via our GetCities controller
            $.ajax({
                url: '/location/index/getcities',
                type: 'GET',
                dataType: 'json'
            }).done(function(response) {
                var cities = response.cities;
                var $select = $('#city-select');
                $select.empty();
                $select.append('<option value="">' + 'Please select' + '</option>');
                // Populate the select with city names. Here we assume each city data object has a "name" field.
                $.each(cities, function(index, city) {
                    // Adjust property name if API returns a different structure
                    if(city.name) {
                        $select.append('<option value="' + city.name + '">' + city.name + '</option>');
                    }
                });
            }).fail(function() {
                $('#city-error').text('Failed to load city list.').show();
            });
        }
    };
});
