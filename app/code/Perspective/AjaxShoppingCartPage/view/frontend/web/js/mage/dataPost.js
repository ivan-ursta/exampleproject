define(['jquery'], function ($) {
    'use strict';

    return function (dataPost) {
        return $.widget('mage.dataPost', dataPost, {
            /**
             * Override _postDataAction so that if the clicked element
             * is a delete link (has the class "action-delete") it does nothing.
             */
            _postDataAction: function (e) {
                // If the element is a delete link, do nothing.
                if ($(e.currentTarget).hasClass('action-delete')) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    return;
                }
                // Otherwise, proceed as usual.
                e.preventDefault();
                this.postData($(e.currentTarget).data('post'));
            }
        });
    };
});
