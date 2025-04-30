(function ($) {
    $(document.body).on('adding_to_cart', function () {
        $('#cart-btn').trigger('click');
    });
})(jQuery);