(function ($) {
    $('#tab-title-reviews').trigger('click');
    $(document.body).on('click', '.woocommerce-review-link', function () {
        var $anchor = $(this.hash);
        if ( $anchor.length ) {
            $('html, body').animate( { scrollTop: $anchor.offset().top }, 500);
            $anchor.trigger('click');
        }
    });
})(jQuery);