@extends('adminlte::page')

@section('title', 'Calculator')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Calculator</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Home</a></li>
                <li class="breadcrumb-item active">Calculator</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <form class="calculator-form" id="apec-homes">
                    <div class="form-group total_contract_price">
                        <label for="total_contract_price">Total Contract Price</label>
                        <input type="text" name="total_contract_price" id="total_contract_price" class="form-control">
                    </div>
                    <div class="form-group discount">
                        <label for="discount">Discount</label>
                        <input type="text" name="discount" id="discount" class="form-control">
                    </div>
                    <div class="form-group reservation_fee">
                        <label for="reservation_fee">Reservation Fee</label>
                        <input type="text" name="reservation_fee" id="reservation_fee" class="form-control">
                    </div>
                    <div class="form-group loanable_amount">
                        <label for="loanable_amount">Loanable Amount</label>
                        <input type="text" name="loanable_amount" id="loanable_amount" class="form-control">
                    </div>
                    <div class="form-group months">
                        <label for="months">Months to pay</label>
                        <input type="text" name="months" id="months" class="form-control">
                    </div>
                    <input type="submit" class="btn btn-primary btn-sm" name="submit" value="Calculate">
                </form>
                <p class="calculation-result"></p>
            </div>
        </div>
    </div>
@stop

@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('vendor/summernote/summernote-bs4.css')}}">
    <style>
    </style>
@stop

@section('js')
    @can('add canned message')
        <script src="{{asset('vendor/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
{{--        <script src="{{asset('js/validation.js')}}"></script>--}}
{{--        <script src="{{asset('js/canned.js')}}"></script>--}}
{{--        <script src="{{asset('js/cannedCategory.js')}}"></script>--}}
        <script src="{{asset('vendor/moment/moment.min.js')}}"></script>
        <script src="{{asset('vendor/daterangepicker/daterangepicker.js')}}"></script>
        <script src="{{asset('vendor/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script><!-- Summernote -->
        <script src="{{asset('vendor/summernote/summernote-bs4.min.js')}}"></script>
        <script>

            $(function () {
                $('#canned-category-list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! route('canned.category.list') !!}',
                    columns: [
                        { data: 'name', name: 'name', orderable: false, searchable: false},
                        { data: 'action', name: 'action', orderable: false, searchable: false}
                    ],
                    responsive:true,
                    order:[0,'desc'],
                    ordering: false,
                    searching: false,
                    paging: false,
                    info:false
                });
            });
        </script>
        <script>
            $(document).on('submit','#apec-homes',function(form){
                form.preventDefault();
                let data = $(this).serializeArray();
                console.log(data[0].value);

                let total_contract_price = data[0].value,
                    discount = data[1].value,
                    reservation_fee = data[2].value,
                    loanable_amount = data[3].value,
                    months_to_pay = data[4].value;

                let discounted_tcp = total_contract_price-discount,
                    discounted_tcp_less_rf = discounted_tcp-reservation_fee,
                    discounted_tcp_less_loanable = discounted_tcp_less_rf-loanable_amount,
                    man_NEP = discounted_tcp_less_loanable/months_to_pay;

                $('.calculation-result').html(function(){
                    let content = '<div class="callout callout-info" style="margin-top:10px;">' +
                        '<strong>Total Contract Price: </strong>'+numberWithCommas(total_contract_price)+'<br/>' +
                        '<strong>Discount: </strong>'+numberWithCommas(discount)+'<br/>' +
                        '<strong>Discount Total Contract Price: </strong>'+numberWithCommas(discounted_tcp)+'<br/>' +
                        '<strong>Reservation Fee: </strong>'+numberWithCommas(reservation_fee)+'<br/>' +
                        '<strong>Loanable Amount: </strong>'+numberWithCommas(loanable_amount)+'<br/>' +
                        '<strong>Net Equity Payment: </strong>'+numberWithCommas(discounted_tcp_less_loanable)+'<br/>' +
                        '<strong>Months to pay: </strong>'+months_to_pay+' months<br/>' +
                        '<strong>Monthly Amort. NEP: </strong>'+numberWithCommas(man_NEP.toFixed(2))+'<br/>' +
                        '</div>';

                    return content;
                });

                function numberWithCommas(n) {
                    var parts=n.toString().split(".");
                    return parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",") + (parts[1] ? "." + parts[1] : "");
                }
            });
        </script>
    @endcan
@stop
