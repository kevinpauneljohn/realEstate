/*requirements template dropdown*/
$(document).on('change','#template',function(){
    let value = this.value;
    let container = $('#sales-requirements-form');
    if(value != "")
    {
        $.ajax({
            'url' : '/get-requirements-by-template/'+value,
            'type' : 'GET',
            beforeSend: function(){
                $('.selected-table, .sales-requirements-btn').remove();
                $('.spinner').show();
            },
            success: function(result){
                $('.spinner').hide();
                container.append('' +
                    '<table class="table table-bordered selected-table">' +
                    '<tr><td><strong>Title</strong></td><td>'+result.template.name+'</td></tr>' +
                    '<tr><td><strong>Financing Type</strong></td><td>'+result.template.type+'</td></tr>' +
                    '<tr><td colspan="2"><h4>Requirements</h4></td></tr></table>'
                );

                $.each(result.requirements, function (key,value) {
                    // console.log(key+' '+value.description);
                    $('.selected-table').append('' +
                        '<tr><td colspan="2">'+value.description+'</td></tr>'
                    );
                });

                container.append('<button type="submit" class="btn btn-primary sales-requirements-btn"><i class="fa fa-save"></i> Save</button>');
            },error: function (xhr, status, error) {
                toastr.error(status+' - '+error);

            }
        });
    }
});

/*save requirements template*/
$(document).on('submit','#sales-requirements-form',function(form){
    form.preventDefault();

    let data = $('#sales-requirements-form');

    $.ajax({
        'url' : '/save-requirements-template',
        'type' : 'PUT',
        'data' : data.serialize(),
        beforeSend: function(){
            $('.sales-requirements-btn').attr('disabled',true);
        },
        success: function (result) {
            console.log(result);

            if(result.success === true)
            {
                toastr.success('Requirements Successfully Saved!');
                setTimeout(function(){
                    setTimeout(function(){
                            location.reload();
                    },1500);
                });
            }else{
                toastr.error('An error occurred');
            }

            $('.sales-requirements-btn').attr('disabled',false);
        },error: function (xhr, status, error) {
            $.each(xhr, function (key, value) {
                console.log(key+' - '+value);
            });
            console.log(status+' : '+error);
            toastr.error(status+' : '+error);
        }
    });
});
