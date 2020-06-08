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

$(document).on('submit','#edit-rank-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/rank/'+data[2].value,
        'type' : 'PUT',
        'data' : data,
        beforeSend: function(){
            $('#edit-rank-form input, #edit-rank-form select, #edit-rank-form textarea').attr('disabled',true);
        },success: function(result){

            if(result.success === true)
            {
                toastr.success(result.message)
                let table = $('#rank-list').DataTable();
                table.ajax.reload();

                $('#edit-rank-form').trigger('reset');
                $('#edit-rank-modal').modal('toggle');

            }else if(result.success === false)
            {
                toastr.error(result.message);
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('#edit-rank-form input, #edit-rank-form select, #edit-rank-form textarea').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('edit_rank','edit_start_points','edit_end_points','edit_description','edit_time_line');
});

$(document).on('click','.delete-rank-btn',function(){
    let id = this.id;

    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.value) {

            $.ajax({
                'url' : '/rank/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );

                        let table = $('#rank-list').DataTable();
                        table.ajax.reload();
                    }else{
                        toastr.error(output.message);
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});
