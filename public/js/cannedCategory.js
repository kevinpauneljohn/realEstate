$(document).on('submit','.category-form',function(form){
    form.preventDefault();

    let data = $(this).serializeArray();

    $.ajax({
        'url' : '/canned-category',
        'type' : 'POST',
        'data' : data,
        beforeSend: function () {
            $('.category-btn').val('Saving ... ').attr('disabled',true);
        },success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                $('.category-form').trigger('reset');
                $('.canned-category').append('<option value="'+result.catValue.id+'">'+result.catValue.name+'</option>');
                toastr.success(result.message);

                var table = $('#canned-category-list').DataTable();
                table.ajax.reload();
            }

            $.each(result, function (key, value) {
                let element = $('.'+key);

                element.find('.error-'+key).remove();
                element.append('<p class="text-danger error-'+key+'">'+value+'</p>');
            });

            $('.category-btn').val('Save').attr('disabled',false);
        },error: function(xhr, status, error){
            console.log(xhr);
        }
    });
    clear_errors('category_name');
});


$(document).on('click','.delete-category',function(){
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
                'url' : '/canned-category/'+id,
                'type' : 'DELETE',
                'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                'data' : {'_method':'DELETE','id' : id},
                beforeSend: function(){

                },success: function(output){
                    if(output.success === true){
                        $('.canned-category option[value='+id+']').remove();
                        Swal.fire(
                            'Deleted!',
                            output.message,
                            'success'
                        );

                        let table = $('#canned-category-list').DataTable();
                        table.ajax.reload();
                    }else if(output.success === false){
                        toastr.error(output.message);

                        if(result.reload === true)
                        {
                            setTimeout(function(){
                                location.reload();
                            },1000);
                        }
                    }
                },error: function(xhr, status, error){
                    console.log(xhr);
                }
            });

        }
    });
});

