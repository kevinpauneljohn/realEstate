$(document).on('click','.accordion-dropdown',function(){
    let id = this.id;
    if($(this).hasClass('collapsed'))
    {
        $('#'+id).html('<i class="fas fa-plus"></i>');
    }else{
        $('#'+id).html('<i class="fas fa-minus"></i>');
    }
});

$(document).on('click','.copy-canned',function(){
    let id = this.id;
    copyToClipboard('#canned-body-'+id);
});
function copyToClipboard(element) {
    var $temp = $("<textarea>");
    $("body").append($temp);
    $temp.val($(element).text().trim()).select();
    document.execCommand("copy");
    toastr.success('Canned message was copied');
    $temp.remove();
}
