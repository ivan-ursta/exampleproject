define([
    'jquery',
    'Perspective_Extension/js/jquery.inputmask.bundle'
], function ($) {
    'use strict';

    return function () {
        setTimeout(function() {
            $('input[name$="telephone"]').inputmask({"mask": "+99(999) 999 99 99"});
        }, 6000);
    };
});
