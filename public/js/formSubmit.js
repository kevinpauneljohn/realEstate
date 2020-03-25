(function () {
    $('.form-submit').on('submit',function () {
        $('.submit-form-btn').attr('disabled',true);
        $('.spinner').show();
    });
})();
