let contestForm = $('.form-submit');
$(document).on('submit','#contest-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/contest',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            contestForm.find('.text-danger').remove();
            contestForm.find('button[type=submit]').attr('disabled',true).text('Saving...');
        }
    }).done( (response, status, xhr) => {
        console.log(response)
        $('#contest-list').DataTable().ajax.reload(null, false);
    }).fail((xhr, status, error) => {
        console.log(xhr)
        $.each(xhr.responseJSON.errors, function(key, value){
            contestForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
        })
    }).always(() => {
        contestForm.find('button[type=submit]').attr('disabled',false).text('Save');
    });
});

let contestModal = $('#add-new-contest-modal');
let contestId;
$(document).on('click','.add-contest-btn', function(){

    contestModal.find('.modal-title').text('Add New Contest')
    contestModal.find('.form-submit').attr('id','contest-form')
    contestModal.find('input[name=id]').remove();
    contestForm.find('#rank').val(null).change();
    contestForm.trigger('reset');
})
$(document).on('click','.edit-rank-btn', function(){
    contestId = this.id;
    contestForm.find('.text-danger, input[name=id]').remove();
    contestModal.find('.modal-title').text('Edit Contest')
    contestModal.find('.form-submit').attr('id','edit-contest-form')
    contestModal.modal('toggle')

    $.ajax({
        url: '/contest/'+contestId+'/edit',
        type: 'get',
        beforeSend: () => {
            contestModal.find('.modal-body').append('<input type="hidden" name="id" value="'+contestId+'">');
        }
    }).done( (response, status, xhr) => {
        console.log(response.ranks)
        if(response.active === 1)
        {
            $('input[name=is_active]').prop('checked',true)
        }else{
            $('input[name=is_active]').prop('checked',false)
        }
        contestForm.find('input[name=title]').val(response.name);
        // contestForm.find('textarea[name=description]').val(response.description);
        contestForm.find('textarea[name=description]').val(function () {
            let regex = /(<([^>]+)>)/ig
            let body = response.description;
            let content = body.replace(regex, "");

            return content;
        });
        contestForm.find('input[name=date_active]').val(moment(response.date_working).format('YYYY-MM-DD'));
        contestForm.find('#rank').val(response.ranks).change();
        contestForm.find('input[name=amount]').val(response.extra_field.amount);
        contestForm.find('input[name=points]').val(response.extra_field.points);
        contestForm.find('input[name=item]').val(response.extra_field.item);
    }).fail( (xhr, status, error) => {
        console.log(xhr)
    }).always( () => {

    });
})

$(document).on('submit','#edit-contest-form',function(form){
    form.preventDefault();
    let data = $(this).serializeArray();

    $.ajax({
        url: '/contest/'+contestId,
        type: 'PUT',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: data,
        beforeSend: () => {
            contestForm.find('.text-danger').remove();
            contestForm.find('button[type=submit]').attr('disabled',true).text('Saving...');
        }
    }).done( (response, status, xhr) => {
        if(response.success === true)
        {
            customAlert("success",response.message);
            $('#contest-list').DataTable().ajax.reload(null, false);
        }else if(response.success === false)
        {
            customAlert("warning",response.message);
        }
    }).fail( (xhr, status, error) => {
        console.log(xhr)
        $.each(xhr.responseJSON.errors, function(key, value){
            contestForm.find('.'+key).append('<p class="text-danger">'+value+'</p>');
        })
    }).always( () => {
        contestForm.find('button[type=submit]').attr('disabled',false).text('Save');
    });
})
