$(function(){

    let addPriorityForm = $('#add-priority-form'),
        editPriorityForm = $('#edit-priority-form'),
        deletePriorityForm = $('#delete-priority-form');


    addPriorityForm.submit(function(form){
        form.preventDefault()

        let data = addPriorityForm.serialize();

        submitform(
            '/priorities',
            'POST',
            data,
            true,
            '',
            false,
            ''
        );

        clear_errors('name','description','day','color');
    });


    editPriorityForm.submit(function (form) {
        form.preventDefault();
        let id = $('#priorityId').val();
        submitform(
            '/priorities/'+id,
            'PUT',
            editPriorityForm.serialize(),
            true,
            '',
            false,
            ''
        );
        clear_errors('edit_name','edit_description','edit_day','edit_color');
    });

    deletePriorityForm.submit(function (form) {
        form.preventDefault();
        let data = deletePriorityForm.serializeArray();

        submitform(
            '/priorities/'+data[2].value,
            'DELETE',
            data,
            true,
            '',
            false,''
        );
    });
});


/*edit priority*/
let id;

$(document).on('click','.edit-priority-btn',function () {
    id = this.id;
    let data = dataObject(this);

    $('#priorityId').val(id);

    $('#edit_name').val(data[1]);
    $('#edit_description').val(data[2]);
    $('#edit_day').val(data[3]).change();
    $('#edit_color').val(data[0]);
});


$(document).on('click','.delete-priority-btn',function(){
    let data;
    id = this.id;

    data = dataObject(this);

    $('#deletePriorityId').val(id);
    $('.delete-priority-name').html('<span style="color:yellow;">'+data[1]+'</span>');
});

