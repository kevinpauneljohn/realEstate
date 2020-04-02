function clear_errors()
{
    let i;
    for (i = 0; i < arguments.length; i++) {

        if($('#'+arguments[i]).val().length > 0){
            $('.'+arguments[i]).closest('div.'+arguments[i]).removeClass('has-error').find('.text-danger').remove();
        }
    }
}

function submitform(url , type , data , message , reload = true, elementAttr, consoleLog = true)
{
    $.ajax({
        'url' : url,
        'type' : type,
        'data' : data,
        'cache' : false,
        beforeSend: function(){
            $('.submit-form-btn').attr('disabled',true);
            $('.spinner').show();
        },
        success: function(result, status, xhr){
            if(consoleLog === true)
            {
                console.log(result);
            }
            if(result.success === true)
            {
                setTimeout(function(){
                    toastr.success(message)
                    setTimeout(function(){
                        if(reload === true)
                        {
                            location.reload();
                        }
                    },1500);
                });
            }else{
                $('.submit-form-btn').attr('disabled',false);
                $('.spinner').hide();
            }

            $.each(result, function (key, value) {
                var element = $(elementAttr+'#'+key);

                element.closest(elementAttr+'div.'+key)
                    .addClass(value.length > 0 ? 'has-error' : 'has-success')
                    .find('.text-danger')
                    .remove();
                element.after('<p class="text-danger">'+value+'</p>');
            });

        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
}

$(document).ready(function(){

    let addForm = $('#add-requirements-form');
    addForm.submit(function(form){
        form.preventDefault();

        let data = addForm.serialize();

        submitform(
            '/requirements',
            'POST',
            data,
            'Requirements Successfully Added!',
            true,
            '',
            false,
        );
        clear_errors('project','financing_type');
    });

    $('#edit-requirement-form').submit(function(form){
        form.preventDefault();

        let data = $('#edit-requirement-form').serialize();
        let id = $('#updateRequirementId').val();

        submitform(
            '/requirements/'+id,
            'PUT',
            data,
            'Requirements Successfully Updated!',
            true,
            '',
            false,
        );
        clear_errors('edit_project','edit_financing_type');
    });

    $('#delete-requirements-form').submit(function(form){
        form.preventDefault();

        let data = $('#edit-requirement-form').serialize();
        let id = $('#deleteRequirementsId').val();

        submitform(
            '/requirements/'+id,
            'DELETE',
            data,
            'Requirements Successfully Deleted!',
            true,
            '',
            false,
        );
    });
});

$(document).on('click','.row-description-btn',function(){
    let value = this.value;

    let row = $('.row-description');

    if(value == 'plus')
    {
        $('.desc-inputs').append('<div class="row row-description">\n' +
            '                                    <div class="col-sm-9">\n' +
            '                                        <input type="text" name="description[]" class="form-control description"/>\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-3">\n' +
            '                                        <button type="button" class="btn btn-success row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
            '                                        <button type="button" class="btn btn-danger row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
            '                                    </div>\n' +
            '                                </div>');
    }else{
        this.closest('.row-description').remove();
    }
});

$(document).on('click','.edit-row-description-btn',function(){
    let value = this.value;

    let row = $('.edit-row-description');

    if(value == 'plus')
    {
        $('.edit-desc-inputs').append('<div class="row edit-row-description">\n' +
            '                                    <div class="col-sm-9">\n' +
            '                                        <input type="text" name="edit_description[]" class="form-control edit_description"/>\n' +
            '                                    </div>\n' +
            '                                    <div class="col-sm-3">\n' +
            '                                        <button type="button" class="btn btn-success edit-row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
            '                                        <button type="button" class="btn btn-danger edit-row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
            '                                    </div>\n' +
            '                                </div>');
    }else{
        this.closest('.edit-row-description').remove();
    }
});

$(document).on('click','.edit-btn',function(){
    let id = this.id;


    $.ajax({
        'url' : '/get-requirements',
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id':id},
        beforeSend: function(){
            $('.edit-row-description').remove();
        },
        success: function(result){
            $('#updateRequirementId').val(id);
            $('#edit_title').val(result.requirements.title);
            $('#edit_project').val(result.project).change();
            $('#edit_financing_type').val(result.requirements.type).change();

            $.each(result.description, function (key, value) {
                $('.edit-desc-inputs').append('<div class="row edit-row-description">\n' +
                    '                                    <div class="col-sm-9">\n' +
                    '                                        <input type="text" name="edit_description[]" class="form-control edit_description" value="'+value+'"/>\n' +
                    '                                    </div>\n' +
                    '                                    <div class="col-sm-3">\n' +
                    '                                        <button type="button" class="btn btn-success edit-row-description-btn" value="plus"><i class="fa fa-plus"></i></button>\n' +
                    '                                        <button type="button" class="btn btn-danger edit-row-description-btn" value="minus"><i class="fa fa-minus"></i></button>\n' +
                    '                                    </div>\n' +
                    '                                </div>');
            });
        }
    });
});

$(document).on('click','.delete-requirements-btn',function(){
    let id = this.id;

    $('#deleteRequirementsId').val(id);
});
