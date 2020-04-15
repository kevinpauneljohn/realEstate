$(function () {
    let addActionForm = $('#add-action-form'),
        editActionForm = $('#edit-action-form'),
        deleteActionForm = $('#delete-action-form');

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

    editActionForm.submit(function (form) {
        form.preventDefault();

        let data = editActionForm.serializeArray();

        submitform(
            '/actions/'+data[2].value,
            'PUT',data,true,'',true,''
        )
    });

    deleteActionForm.submit(function (form) {
        form.preventDefault();

        let data = deleteActionForm.serializeArray();
        console.log(data);
        submitform('/actions/'+data[2].value,'DELETE',data,true,'',true,'');
    });
});


let id;
$(document).on('click','.edit-action-btn',function () {
    id = this.id;

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
});

$(document).on('click','.delete-action-btn',function () {
    id = this.id;
    let data = dataObject(this);
    $('#deleteActionId').val(id);
    $('.delete-action-name').text(data[1]).attr('style','color:yellow');
    console.log(data);
});
