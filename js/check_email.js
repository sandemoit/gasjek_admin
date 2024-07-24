$(function() {
    const $checkEmailButton = $('#checkEmailButton');
    const $emailInput = $('#emailInput');
    const $loading = $('#loading');
    const $validasi = $('#validasi');
    const $emailInfo = $('#emailInfo');
    const $nominalInput = $('#nominalInput');
    const $saveButton = $('#saveButton');

    $checkEmailButton.on('click', function() {
        const email = $emailInput.val();
        if (email) {
            $loading.show();
            $.post('wallet/check_email', { email })
                .done(response => {
                    $loading.hide();
                    $validasi.toggle(!response.exists);
                    $emailInfo.toggle(response.exists);
                    $nominalInput.prop('disabled', !response.exists);
                    $saveButton.prop('disabled', !response.exists);
                })
                .fail(() => {
                    $loading.hide();
                    alert('Error pengecekan email.');
                });
        } else {
            alert('Email field is required.');
        }
    });
});
