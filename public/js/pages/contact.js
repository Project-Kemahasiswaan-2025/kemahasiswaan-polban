$(document).ready(function() {
    const form = $('#contact-form');
    const submitBtn = $('#submit-btn');
    const alertContainer = $('#alert-container');
    const alertBox = alertContainer.find('.alert');
    const alertIcon = $('#alert-icon');
    const alertMessage = $('#alert-message');

    form.on('submit', function(e) {
        e.preventDefault();
        
        const sendingTxt = submitBtn.data('sending');
        const defaultTxt = submitBtn.data('default');
        
        // Disable button
        submitBtn.prop('disabled', true).html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${sendingTxt}`);
        alertContainer.addClass('d-none');

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);

        $.ajax({
            url: '/api/contact-tickets',
            method: 'POST',
            data: data,
            success: function(response) {
                // Success
                alertBox.removeClass('alert-danger').addClass('alert-success');
                alertIcon.removeClass('bi-exclamation-triangle-fill').addClass('bi-check-circle-fill');
                alertMessage.html(`<strong>Berhasil!</strong> ${response.message}`);
                alertContainer.removeClass('d-none');
                
                // Clear form
                form[0].reset();
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }

                submitBtn.prop('disabled', false).html(`<i class="bi bi-send me-2"></i>${defaultTxt}`);
            },
            error: function(xhr) {
                // Error
                const message = xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan sistem.';
                alertBox.removeClass('alert-success').addClass('alert-danger');
                alertIcon.removeClass('bi-check-circle-fill').addClass('bi-exclamation-triangle-fill');
                alertMessage.html(`<strong>Oops!</strong> ${message}`);
                alertContainer.removeClass('d-none');
                
                submitBtn.prop('disabled', false).html(`<i class="bi bi-send me-2"></i>${defaultTxt}`);
            }
        });
    });
});
