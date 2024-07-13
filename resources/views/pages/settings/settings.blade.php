@extends('adminlte::page')

@section('title', 'Project Profile')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Settings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </div><!-- /.col -->
    </div>
@stop

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-body">
                <form class="turn-off-sensitive-info-form">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <label for="sensitive-data">Hide Sensitive Data</label>
                            <x-adminlte-select-bs name="sensitive_data">
                                <option value=""> -- select -- </option>
                                <option value="hide">Hide</option>
                                <option value="show">Show</option>
                            </x-adminlte-select-bs>
                        </div>
                        <div class="col-6">
                            <br/>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@stop

@section('right-sidebar')
    <x-custom.right-sidebar />
@stop
@section('css')


@stop

@section('js')
    <script>
        $(document).on('submit','.turn-off-sensitive-info-form',function(form){
            form.preventDefault();
            let data = $(this).serializeArray();
            $.ajax({
                url: '/hide-sensitive-content',
                type: 'post',
                data: data,
                beforeSend: function (){

                }
            }).done(function(response){
                console.log(response)
                if(response.success === true)
                {
                    setTimeout(function(){
                        window.location.reload();
                    },1500)
                }
            }).fail(function(xhr, status, error){
                console.log(xhr)
            })
        })
    </script>
@stop
