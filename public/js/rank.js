$(document).on('submit','#rank-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();
    // console.log(data);

    $.ajax({
        'url' : '/rank',
        'type' : 'POST',
        'data' : data,
        beforeSend: function(){
            $('.rank-btn').val('Saving ... ').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                toastr.success(result.message);
                let table = $('#rank-list').DataTable();
                table.ajax.reload();

                $('#rank-form').trigger('reset');
                $('#create-rank-modal').modal('toggle');
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });
            $('.rank-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });

    clear_errors('rank','start_points','end_points','description','time_line');
});

$(document).on('click','.edit-rank-btn',function(){
    let id = this.id;

    $.ajax({
        'url' : '/rank/'+id,
        'type' : 'POST',
        'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        'data' : {'id' : id},
        beforeSend: function(){
            $('#edit-rank-form input, #edit-rank-form select, #edit-rank-form textarea').attr('disabled',true);
        },success: function(result){
            console.log(result);
            $('#rank_id').val(id);
            $('#edit_rank').val(result.name);
            $('#edit_start_points').val(result.start_points);
            $('#edit_end_points').val(result.end_points);
            $('#edit_time_line').val(result.timeline);
            $('#edit_description').val(result.description);

            $('#edit-rank-form input, #edit-rank-form select, #edit-rank-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
});
