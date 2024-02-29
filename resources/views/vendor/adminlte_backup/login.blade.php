@extends('adminlte::master')

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('/css/style.css')}}">
@stop

@section('adminlte_css')
    @stack('css')
    <style type="text/css">
        .errors{
            text-align:center;
        }
        .spinner{
            display:none;
        }
        .show-password{
            display: none;
        }
    </style>
    @yield('css')
@stop

@section('classes_body', 'login-page')

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ $dashboard_url }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <div class="card">
            <div class="card-body login-card-body">
                @if(session('success') === false)
                    <div class="alert alert-danger">
                        <i class="fa fa-warning"></i> Invalid Credentials
                    </div>
                @endif
                @if($errors->has('email'))
                    <div class="alert alert-danger">
                        <i class="fa fa-warning"></i> {{$errors->first('email')}}
                    </div>
                @endif
                @if(session('attempts'))
                    <div class="errors">{{4-session('attempts')}} Attempt(s) Left</div>
                @endif
                <p class="login-box-msg">{{ __('adminlte::adminlte.login_message') }}</p>
                <form action="{{ route('authenticate') }}" method="post" class="form-submit">
                    {{ csrf_field() }}
                    <div class="input-group mb-3">
                        <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}" placeholder="Username" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                        @if ($errors->has('username'))
                            <div class="invalid-feedback">
                                {{ $errors->first('username') }}
                            </div>
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="{{ __('adminlte::adminlte.password') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-eye mb-md-n1 show-password" style="margin-right: 5px;cursor: pointer" title="Show password"></span>
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary btn-block btn-flat submit-form-btn">
                                <i class="spinner fa fa-spinner fa-spin"></i> {{ __('adminlte::adminlte.sign_in') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script src="{{asset('js/formSubmit.js')}}"></script>
    <script>
        $(document).on('mouseenter','input[name=password], .show-password',function(){
            $('.show-password').show();
        });

        $(document).on('mouseleave','input[name=password], .show-password',function(){
            $('.show-password').hide();
        });

        $(document).on('click','.show-password',function(){
            $('input[name="password"]').hasClass('show')
                ? $('input[name="password"]').removeClass('show').addClass('hide').prop('type','password')
                : $('input[name="password"]').removeClass('hide').addClass('show').prop('type','text')

            $('.show-password').hasClass('fa-eye')
                ? $('.show-password').removeClass('fa-eye').addClass('fa-eye-slash').attr('title','Hide password')
                : $('.show-password').removeClass('fa-eye-slash').addClass('fa-eye').attr('title','Show password')
        });
    </script>
    @stack('js')
    @yield('js')
@stop
