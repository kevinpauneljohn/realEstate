<!-- Date range -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
        </div>
        <input type="text" name="date" class="form-control float-right" id="sales-dates">
    </div>
    <!-- /.input group -->
</div>

<!-- /.form group -->
@section('plugins.Moment',true)
@section('plugins.DateRangePicker',true)

@section('plugins.tempusdominusBootstrap4',true)
@push('js')
    <script>
        $(document).ready(function(){
            //Date range picker
            $('#sales-dates').daterangepicker();
            setTimeout(function(){
                $('#sales-dates').val('{{now()->startOfYear()->format('m/d/Y')}} - {{now()->endOfYear()->format('m/d/Y')}}').change();
            },1500)
        });
        $(document).on('change','#sales-dates',function(){
            let date = $(this).val();
            $.ajax({
                url: '{!! route('sales.date.range') !!}',
                type: 'POST',
                data: {'date' : date},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            }).done((result) => {
                console.log(result)
                $('#sales-list').DataTable().ajax.reload(null, false);
            });
        })
    </script>
@endpush
