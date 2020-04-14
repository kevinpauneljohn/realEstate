$(function () {
    let addActionForm = $('#add-action-form');

    addActionForm.submit(function (form) {
        form.preventDefault();

        let data = addActionForm.serializeArray();

        submitform(
            '/actions',
            'POST',
            data,true,'',false,''
        );

        clear_errors('action','description','priority');
    });
});


let id;
$(document).on('click','.edit-action-btn',function () {
    id = this.id;
    let data = dataObject(this);
    $('#actionId').val(id);

    $.ajax({
        'url' : '/action/get/'+id,
        'type' : 'GET',
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },success: function (result) {

            $('#edit_action').val(result.name);
            $('#edit_description').val(result.description);
            $('#edit_priority').val(result.priority_id).change();

            $('.submit-form-btn').attr('disabled',false);
            $('.spinner').hide();
        }

    });

    // console.log(data);
});
