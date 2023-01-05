(function ($) {
    window.scrollToActiveElement = function (className) {
        const activeElement = document.querySelector(className);

        if (activeElement) {
            activeElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    };


    // check addtocart validation
    window.validateForm = function () {

        var select_at_least = $('#select_at_least').attr('href');
        var options = $('#options').attr('href');
        
        var isValid = true;

        $('.item_extra_list.required-section').each(function () {
            var section = $(this);
            var requiredCount = parseInt(section.data('limit'));
            var inputs = section.find('.extras:checked');

            if (inputs.length < requiredCount) {
                isValid = false;
                section.find('.errorMessage').text(`${select_at_least} ${requiredCount} ${options}`);
                scrollToActiveElement('.errorMessage');
            } else {
                section.find('.errorMessage').text('');
            }
        });
        setTimeout(() => {
            $('.errorMessage').html('');
        }, 4000);

        return isValid;
    }

})(jQuery);