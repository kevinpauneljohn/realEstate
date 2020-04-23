// let id;
// $(document).on('click','.view-request-btn',function () {
//     id = this.id;
//
//     $.ajax({
//         'url' : '/requests/'+id,
//         'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
//         'type' : 'POST',
//         beforeSend: function(){
//             $('.spinner').show().closest('div').attr('align','center');
//             $('.request-data, .role').html("");
//         },
//         success: function(result){
//             $('.spinner').hide().closest('div').removeAttr('align');
//             console.log(result);
//             $('.username').html('<a href="#">'+result.user.firstname+' '+result.user.lastname+'</a>');
//             $('.reason').text(result.description);
//             $('#request-type').text(result.type+' '+result.storage_name).css('font-weight','bold');
//             $('#priority').text(result.priority.name).css({'font-weight' : 'bold','color' : result.priority.color});
//             $('#request-status').text(result.status).css('font-weight','bold');
//
//             $.each(result.data,function (key, value) {
//                 $('.request-data').append('<tr><td>'+key+'</td><td class="data-value">'+value+'</td></tr>');
//             });
//
//             $.each(result.user.roles, function (key, value) {
//                 $('.role').append('<span style="color: dodgerblue;">'+value.name+'</span>, ');
//             });
//
//         },error: function(xhr, status, error){
//             $.each(xhr, function (key, value) {
//                 console.log('Key: '+key+' Value: '+value);
//             });
//
//             console.log('Status: '+status+' Error: '+error);
//         }
//     });
// });
