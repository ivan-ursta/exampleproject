define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/jquery.cookie'
], function($) {
    'use strict';
    return {
        init: function() {
            var self = this;
            // On page load, if no city is saved, try to auto-detect it.
            var savedCity = $.cookie('selected_city');
            if (!savedCity) {
                self.detectCity();
            } else {
                $('#location-city').text(savedCity);
            }
            // When the header (location-menu) is clicked, open the modal for manual selection.
            $('#location-menu').on('click', function() {
                self.openModal();
            });
            // When a city is selected from the dropdown, update cookie and header.
            $('#city-select').on('change', function() {
                var selectedCity = $(this).val();
                if (selectedCity) {
                    $.cookie('selected_city', selectedCity, { expires: 30, path: '/' });
                    $('#location-city').text(selectedCity);
                    $('#location-modal').modal('closeModal');
                }
            });
        },
        detectCity: function() {
            var self = this;
            $.ajax({
                url: '/location/index/getcity',
                type: 'GET',
                dataType: 'json',
                timeout: 5000
            }).done(function(response) {
                var city = response.city;
                if (city) {
                    // Save detected city and update header.
                    $.cookie('selected_city', city, { expires: 30, path: '/' });
                    $('#location-city').text(city);
                } else {
                    // If detection fails, nothing is saved; user can manually select later.
                    console.log('City detection returned empty.');
                }
            }).fail(function() {
                console.log('City detection AJAX request failed.');
            });
        },
        openModal: function() {
            var self = this;
            $('#location-modal').modal({
                title: 'Choose your city',
                buttons: []
            }).modal('openModal');
            // Load the city list into the dropdown if not already loaded.
            self.loadCityList();
        },
        loadCityList: function() {
            var $select = $('#city-select');
            // If the dropdown already has more than one option, do not reload.
            if ($select.find('option').length > 1) {
                return;
            }
            $.ajax({
                url: '/location/index/getcities',
                type: 'GET',
                data: { limit: 50 }, // Request up to 50 cities.
                dataType: 'json',
                timeout: 5000
            }).done(function(response) {
                var cities = response.cities;
                $select.empty();

                // Populate the select element with city names (assuming each city object has a "name" property).
                $.each(cities, function(index, city) {
                    if (city.name) {
                        $select.append('<option value="' + city.name + '">' + city.name + '</option>');
                    }
                });
            }).fail(function() {
                $('#city-error').text('Failed to load city list.').show();
            });
        }
    };
});
