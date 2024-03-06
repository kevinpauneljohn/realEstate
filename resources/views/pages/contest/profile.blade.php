@extends('adminlte::page')

@section('title', 'Contest')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Contest Details</h1>
    </div><!-- /.col -->
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{route('contest.index')}}">Contests</a> </li>
            <li class="breadcrumb-item active">Contest Details</li>
        </ol>
    </div><!-- /.col -->
</div>
@stop

@section('content')
    <div class="card contest-card container-fluid">
        <div class="card-header">
            <h3 class="card-title">
                {{ucwords($contest->name)}}
            </h3>
            <div class="card-tools">
                @if($allowedToJoin === true)
                    @if($is_user_joined_the_contest === true)
                        <button type="button" class="btn btn-success btn-sm" disabled>Joined</button>
                    @else
                        <button type="button" class="btn btn-success btn-sm" id="join-btn">Join</button>
                    @endif
                @endif

            </div>

        </div>
        <div class="card-body">
            <p class="contest-description">
                {!! $contest->description !!}
            </p>
        </div>
    </div>

    <div class="card contest-card container-fluid">
        <div class="card-header">
            <h3 class="card-title">Participants</h3>
        </div>
        <div class="card-body">

            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <table id="participants-list" class="table table-bordered table-striped table-hover" role="grid">
                    <thead>
                    <tr role="row">
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Rank</th>
                        <th width="20%"></th>
                    </tr>
                    </thead>

                    <tfoot>
                    <tr role="row">
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Rank</th>
                        <th width="10%"></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@stop

@section('right-sidebar')
<x-custom.right-sidebar />
@stop

@section('css')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{asset('/css/style.css')}}">
<link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/daterangepicker/daterangepicker.css')}}">
<link rel="stylesheet" href="{{asset('/vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
@stop

@section('js')
<script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
<!-- bootstrap datepicker -->
<script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
<script src="{{asset('/vendor/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('/vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<script src="{{asset('js/custom-alert.js')}}"></script>
<script src="{{asset('js/contest.js')}}"></script>

    @can('view contest')
        <script>
            $(function() {
                $('#participants-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('get-contest-participants',['contest' => $contest->id]) !!}',
                    columns: [
                        { data: 'fullName', name: 'fullName'},
                        { data: 'username', name: 'username'},
                        { data: 'rank', name: 'rank'},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc']
                });
            });

            $(document).on('click','#join-btn', function(){

                $.ajax({
                    url: '/join-contest/{{$contest->id}}',
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    beforeSend: () => {
                        $('#join-btn').attr('disabled',true).text('Joining...')
                    }
                }).done( (response, status, xhr) => {
                    console.log(response)
                    if(response.success === true)
                    {
                        customAlert("success",response.message);
                    }else if(response.success === false)
                    {
                        customAlert("warning",response.message);
                    }

                }).fail( (xhr, status, error) => {
                    console.log(xhr)
                }).always( () => {
                    $('#join-btn').text('Joined')
                })
            });
        </script>
    @endcan

    @can('declare contest winner')
        <script>
            let winnerId;
            $(document).on('click','.declare-winner-btn',function () {
                winnerId = this.id;
                console.log(winnerId)

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                Swal.fire({
                    title: 'Declare '+data[0]+' as the winner?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/declare-contest-winner/{{$contest->id}}/'+winnerId,
                            'type' : 'post',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){
                                $('#'+winnerId).html('<div class="spinner-border" role="status" style="height: 15px; width: 15px"><span class="sr-only">Loading...</span></div>');
                                $('.declare-winner-btn').attr('disabled',true)
                            }
                        }).done( (response) => {
                            console.log(response)
                            if(response.success === true)
                            {
                                $('#participants-list').DataTable().ajax.reload(null, false);
                                customAlert("success",response.message);
                            }else if(response.success === false)
                            {
                                customAlert("danger",response.message);
                            }
                        }).fail( (xhr, status, error) => {
                            console.log(xhr)
                        });

                    }
                });
            });
        </script>
    @endcan
@stop
