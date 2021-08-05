@extends('adminlte::page')

@section('title', 'View Requirements')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Payment Schedule</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('sales.index')}}">View Sales</a></li>
                <li class="breadcrumb-item active">Payment Schedule</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4" style="overflow-x:auto;">
                    <table id="payments-list" class="table table-hover" role="grid">
                        <thead>
                        <tr role="row">
                            <th>Due Date</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Model Unit</th>
                            <th>Blk and Lot</th>
                            <th>Amount</th>
                        </tr>
                        </thead>

                        <tfoot>
                        <tr>
                            <th>Due Date</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Model Unit</th>
                            <th>Blk and Lot</th>
                            <th>Amount</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{asset('/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style rel="stylesheet">
        .due-date-now{
            background-color: #fbf4d8;
        }
    </style>
@stop

@section('js')
    <!-- bootstrap datepicker -->
    <script src="{{asset('/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('/vendor/inputmask/min/jquery.inputmask.bundle.min.js')}}"></script>
    @can('view sales')
        <script src="{{asset('js/requirements.js')}}"></script>
        <script>
            $(function() {
                $('#payments-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('sales.payment.this.month') !!}',
                    columns: [
                        { data: 'schedule', name: 'schedule'},
                        { data: 'client', name: 'client'},
                        { data: 'project', name: 'project'},
                        { data: 'modelUnit', name: 'modelUnit'},
                        { data: 'blk_and_lot', name: 'blk_and_lot'},
                        { data: 'amount', name: 'amount'},
                    ],
                    responsive:true,
                    order:[0,'asc']
                });
            });
            //Initialize Select2 Elements
            $('.select2').select2();

            $(document).on('click','.duplicate-requirements-btn',function(){
                let id = this.id;

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();


                Swal.fire({
                    title: 'Duplicate?',
                    text: data[0],
                    type: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, duplicate it!'
                }).then((result) => {
                    if (result.value) {

                        $.ajax({
                            'url' : '/duplicate/'+id,
                            'type' : 'POST',
                            'headers': {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            beforeSend: function(){

                            },success: function(output){
                                console.log(output);
                                if(output.success === true){
                                    let table = $('#requirements-list').DataTable();
                                    table.ajax.reload();

                                    Swal.fire(
                                        'Duplicated!',
                                        'Requirements template has been duplicated.',
                                        'success'
                                    );
                                }
                            },error: function(xhr, status, error){
                                console.log(xhr);
                            }
                        });

                    }
                });
            });
        </script>
    @endcan
@stop
